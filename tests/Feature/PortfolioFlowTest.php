<?php

namespace Tests\Feature;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render(): void
    {
        $this->get('/')->assertOk()->assertSee('Create portfolio');
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();

        foreach (array_keys(Portfolio::TEMPLATES) as $template) {
            $this->get("/samples/{$template}")->assertOk()->assertSee('Andrea Reyes');
        }
    }

    public function test_register_and_land_on_dashboard(): void
    {
        $this->post('/register', [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'password' => 'secret-pass',
            'password_confirmation' => 'secret-pass',
        ])->assertRedirect('/dashboard');

        $this->get('/dashboard')->assertOk()->assertSee('Juan’s portfolios', false);
    }

    public function test_full_required_flow(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['name' => 'Juan Dela Cruz']);
        $this->actingAs($user);

        // Create + personal info (with photo)
        $this->get('/portfolios/create')->assertOk();
        $response = $this->post('/portfolios', [
            'title' => 'Dev portfolio',
            'full_name' => 'Juan Dela Cruz',
            'headline' => 'Developer',
            'email' => 'juan@example.com',
            'phone' => '+63 912 345 6789',
            'address' => 'Manila',
            'about' => "Hello there.\n\nSecond paragraph.",
            'photo' => UploadedFile::fake()->image('me.jpg', 400, 400),
            'intent' => 'next',
        ]);
        $portfolio = Portfolio::firstOrFail();
        $response->assertRedirect(route('portfolios.edit', [$portfolio, 'education']));
        $this->assertNotNull($portfolio->photo_path);
        Storage::disk('public')->assertExists($portfolio->photo_path);

        // Repeatable sections; blank rows are dropped, URLs get a scheme
        $this->put(route('portfolios.update', [$portfolio, 'education']), ['items' => [
            ['school' => 'NSU', 'degree' => 'BSIT', 'start_year' => '2023', 'end_year' => '2027'],
            ['school' => '', 'degree' => '', 'start_year' => '', 'end_year' => ''],
        ], 'intent' => 'next'])->assertRedirect(route('portfolios.edit', [$portfolio, 'skills']));
        $this->assertSame(1, $portfolio->educations()->count());

        $this->put(route('portfolios.update', [$portfolio, 'skills']), ['items' => [
            ['name' => 'PHP', 'level' => 4], ['name' => 'CSS', 'level' => 5], ['name' => '', 'level' => 3],
        ]])->assertRedirect();
        $this->assertSame(2, $portfolio->skills()->count());

        $this->put(route('portfolios.update', [$portfolio, 'projects']), ['items' => [
            ['title' => 'App', 'url' => 'github.com/juan/app', 'tech' => 'Laravel, Vue'],
        ]])->assertSessionHasNoErrors();
        $this->assertSame('https://github.com/juan/app', $portfolio->projects()->first()->url);

        $this->put(route('portfolios.update', [$portfolio, 'experience']), ['items' => [
            ['company' => 'Acme', 'role' => 'Intern', 'start_date' => 'Jun 2026', 'is_current' => '1'],
        ]])->assertSessionHasNoErrors();
        $this->assertTrue($portfolio->experiences()->first()->is_current);

        $this->put(route('portfolios.update', [$portfolio, 'links']), ['items' => [
            ['label' => 'GitHub', 'url' => 'https://github.com/juan'],
        ], 'intent' => 'next'])->assertRedirect(route('portfolios.template', $portfolio));

        // Validation: a row missing its required field is rejected
        $this->put(route('portfolios.update', [$portfolio, 'links']), ['items' => [
            ['label' => '', 'url' => 'https://x.com/juan'],
        ]])->assertSessionHasErrors('items.0.label');

        // Every step page renders with data
        foreach (array_keys(Portfolio::STEPS) as $step) {
            $this->get(route('portfolios.edit', [$portfolio, $step]))->assertOk();
        }

        // Template selection + generate
        $this->get(route('portfolios.template', $portfolio))->assertOk();
        $this->post(route('portfolios.generate', $portfolio), ['template' => 'creative'])
            ->assertRedirect(route('portfolios.preview', $portfolio));
        $portfolio->refresh();
        $this->assertSame('creative', $portfolio->template);
        $this->assertNotNull($portfolio->generated_at);

        // Preview + every template renders with the saved data
        $this->get(route('portfolios.preview', $portfolio))->assertOk();
        foreach (array_keys(Portfolio::TEMPLATES) as $template) {
            $this->get(route('portfolios.render', $portfolio).'?template='.$template)
                ->assertOk()->assertSee('Juan')->assertSee('NSU')->assertSee('PHP');
        }
        $this->get(route('portfolios.show', $portfolio))->assertOk()->assertSee('Back to Folio');

        // Dashboard shows it
        $this->get('/dashboard')->assertOk()->assertSee('Dev portfolio')->assertSee('Generated');

        // Delete removes rows and the photo
        $photo = $portfolio->photo_path;
        $this->delete(route('portfolios.destroy', $portfolio))->assertRedirect('/dashboard');
        $this->assertDatabaseCount('portfolios', 0);
        $this->assertDatabaseCount('educations', 0);
        Storage::disk('public')->assertMissing($photo);
    }

    public function test_sample_portfolio_shortcut(): void
    {
        $this->actingAs(User::factory()->create());
        $this->post('/portfolios/sample')->assertRedirect();
        $portfolio = Portfolio::firstOrFail();
        // Everything but the photo is filled in.
        $this->assertGreaterThanOrEqual(90, $portfolio->completionPercent());
        $this->get('/dashboard')->assertOk()->assertSee('Sample portfolio');
    }

    public function test_other_users_cannot_touch_a_portfolio(): void
    {
        $owner = User::factory()->create();
        $portfolio = $owner->portfolios()->create(['title' => 'Mine', 'full_name' => 'Owner']);

        $this->actingAs(User::factory()->create());
        $this->get(route('portfolios.preview', $portfolio))->assertForbidden();
        $this->get(route('portfolios.show', $portfolio))->assertForbidden();
        $this->get(route('portfolios.edit', $portfolio))->assertForbidden();
        $this->delete(route('portfolios.destroy', $portfolio))->assertForbidden();
        $this->assertDatabaseHas('portfolios', ['id' => $portfolio->id]);
    }

    public function test_sharing_a_portfolio_publicly(): void
    {
        $owner = User::factory()->create();
        $portfolio = $owner->portfolios()->create(['title' => 'Mine', 'full_name' => 'Juan Dela Cruz', 'template' => 'modern']);
        $this->actingAs($owner);

        // Can't share before generating
        $this->put(route('portfolios.share', $portfolio), ['is_public' => 1])->assertSessionHasErrors('share');
        $this->assertFalse($portfolio->fresh()->is_public);

        // Generate, then share: a slug is created from the name
        $this->post(route('portfolios.generate', $portfolio), ['template' => 'creative']);
        $this->put(route('portfolios.share', $portfolio), ['is_public' => 1])->assertSessionHasNoErrors();
        $portfolio->refresh();
        $this->assertTrue($portfolio->isShared());
        $this->assertSame('juan-dela-cruz', $portfolio->slug);
        $this->get(route('portfolios.preview', $portfolio))->assertOk()->assertSee('/p/juan-dela-cruz');

        // A guest can view it, read-only
        auth()->logout();
        $this->get('/p/juan-dela-cruz')->assertOk()->assertSee('Juan')->assertDontSee('Back to Folio');

        // A second person with the same name gets a numbered slug
        $other = User::factory()->create()->portfolios()->create(['title' => 'X', 'full_name' => 'Juan Dela Cruz']);
        $this->assertSame('juan-dela-cruz-2', \App\Models\Portfolio::uniqueSlug('Juan Dela Cruz', $other->id));

        // Changing the link: taken slugs are rejected, new ones work
        $this->actingAs($owner);
        $other->update(['slug' => 'taken-link']);
        $this->put(route('portfolios.share', $portfolio), ['is_public' => 1, 'slug' => 'taken-link'])->assertSessionHasErrors('slug');
        $this->put(route('portfolios.share', $portfolio), ['is_public' => 1, 'slug' => 'Juan DC Portfolio'])->assertSessionHasNoErrors();
        $this->assertSame('juan-dc-portfolio', $portfolio->fresh()->slug);
        $this->get('/p/juan-dela-cruz')->assertNotFound();

        // Turning it off makes the link stop working
        $this->put(route('portfolios.share', $portfolio), ['is_public' => 0]);
        auth()->logout();
        $this->get('/p/juan-dc-portfolio')->assertNotFound();

        // Nobody else can change sharing
        $this->actingAs(User::factory()->create());
        $this->put(route('portfolios.share', $portfolio), ['is_public' => 1])->assertForbidden();
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/portfolios/create')->assertRedirect('/login');
    }
}
