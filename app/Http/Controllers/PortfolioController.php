<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Support\SamplePortfolio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    /**
     * Repeatable sections: relation name, the fields a row holds, and the
     * validation rules for each row.
     */
    private const SECTIONS = [
        'education' => [
            'relation' => 'educations',
            'rules' => [
                'school' => ['required', 'string', 'max:160'],
                'degree' => ['nullable', 'string', 'max:160'],
                'start_year' => ['nullable', 'string', 'max:10'],
                'end_year' => ['nullable', 'string', 'max:10'],
                'description' => ['nullable', 'string', 'max:1000'],
            ],
        ],
        'skills' => [
            'relation' => 'skills',
            'rules' => [
                'name' => ['required', 'string', 'max:80'],
                'level' => ['required', 'integer', 'between:1,5'],
            ],
        ],
        'projects' => [
            'relation' => 'projects',
            'rules' => [
                'title' => ['required', 'string', 'max:160'],
                'role' => ['nullable', 'string', 'max:120'],
                'year' => ['nullable', 'string', 'max:10'],
                'tech' => ['nullable', 'string', 'max:255'],
                'url' => ['nullable', 'url', 'max:255'],
                'description' => ['nullable', 'string', 'max:1000'],
            ],
        ],
        'experience' => [
            'relation' => 'experiences',
            'rules' => [
                'company' => ['required', 'string', 'max:160'],
                'role' => ['required', 'string', 'max:160'],
                'location' => ['nullable', 'string', 'max:120'],
                'start_date' => ['nullable', 'string', 'max:20'],
                'end_date' => ['nullable', 'string', 'max:20'],
                'is_current' => ['boolean'],
                'description' => ['nullable', 'string', 'max:1000'],
            ],
        ],
        'links' => [
            'relation' => 'links',
            'rules' => [
                'label' => ['required', 'string', 'max:60'],
                'url' => ['required', 'url', 'max:255'],
            ],
        ],
    ];

    /** Fields that are filled automatically and don't make a row "non-empty". */
    private const DEFAULTED_FIELDS = ['level', 'is_current'];

    // ── Create ──────────────────────────────────────────────────────────────

    public function create(Request $request): View
    {
        return view('portfolios.steps.personal', [
            'portfolio' => new Portfolio([
                'full_name' => $request->user()->name,
                'email' => $request->user()->email,
            ]),
            'step' => 'personal',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePersonal($request);

        $portfolio = DB::transaction(function () use ($request, $data) {
            $portfolio = $request->user()->portfolios()->create(
                collect($data)->except(['photo', 'remove_photo'])->all() + ['template' => 'simple']
            );

            if ($request->hasFile('photo')) {
                $portfolio->update(['photo_path' => $this->storePhoto($request->file('photo'), $portfolio)]);
            }

            return $portfolio;
        });

        return $this->afterSave($request, $portfolio, 'personal', 'Portfolio created and saved.');
    }

    public function sample(Request $request): RedirectResponse
    {
        $portfolio = SamplePortfolio::createFor($request->user());

        return redirect()->route('portfolios.preview', $portfolio)
            ->with('status', 'Sample portfolio created. Edit any section to make it yours.');
    }

    // ── Stepped sections ────────────────────────────────────────────────────

    public function edit(Portfolio $portfolio, string $step = 'personal'): View
    {
        Gate::authorize('manage', $portfolio);
        abort_unless(array_key_exists($step, Portfolio::STEPS), 404);

        $portfolio->loadCount(['educations', 'skills', 'projects', 'experiences', 'links']);

        if ($step !== 'personal') {
            $portfolio->load(self::SECTIONS[$step]['relation']);
        }

        return view('portfolios.steps.'.$step, compact('portfolio', 'step'));
    }

    public function update(Request $request, Portfolio $portfolio, string $step): RedirectResponse
    {
        Gate::authorize('manage', $portfolio);
        abort_unless(array_key_exists($step, Portfolio::STEPS), 404);

        if ($step === 'personal') {
            $data = $this->validatePersonal($request);
            $attributes = collect($data)->except(['photo', 'remove_photo'])->all();

            if ($request->boolean('remove_photo') && $portfolio->photo_path) {
                $this->deletePhoto($portfolio->photo_path);
                $attributes['photo_path'] = null;
            }

            if ($request->hasFile('photo')) {
                if ($portfolio->photo_path) {
                    $this->deletePhoto($portfolio->photo_path);
                }
                $attributes['photo_path'] = $this->storePhoto($request->file('photo'), $portfolio);
            }

            $portfolio->update($attributes);
        } else {
            $this->saveSection($request, $portfolio, $step);
            $portfolio->touch();
        }

        return $this->afterSave($request, $portfolio, $step, Portfolio::STEPS[$step]['title'].' saved.');
    }

    // ── Template, generate, preview ─────────────────────────────────────────

    public function chooseTemplate(Portfolio $portfolio): View
    {
        Gate::authorize('manage', $portfolio);

        return view('portfolios.template', compact('portfolio'));
    }

    public function generate(Request $request, Portfolio $portfolio): RedirectResponse
    {
        Gate::authorize('manage', $portfolio);

        $data = $request->validate([
            'template' => ['required', Rule::in(array_keys(Portfolio::TEMPLATES))],
        ]);

        $portfolio->update(['template' => $data['template'], 'generated_at' => now()]);

        return redirect()->route('portfolios.preview', $portfolio)
            ->with('status', 'Portfolio generated with the '.$portfolio->templateName().' template.');
    }

    public function preview(Request $request, Portfolio $portfolio): View
    {
        Gate::authorize('manage', $portfolio);

        $template = $request->query('template', $portfolio->template);
        abort_unless(array_key_exists($template, Portfolio::TEMPLATES), 404);

        return view('portfolios.preview', compact('portfolio', 'template'));
    }

    /** The bare portfolio page, used inside preview frames and miniatures. */
    public function render(Request $request, Portfolio $portfolio): View
    {
        Gate::authorize('manage', $portfolio);

        $template = $request->query('template', $portfolio->template);
        abort_unless(array_key_exists($template, Portfolio::TEMPLATES), 404);

        return $this->portfolioView($portfolio, $template, embedded: true);
    }

    /** The generated portfolio, full page. */
    public function show(Portfolio $portfolio): View
    {
        Gate::authorize('manage', $portfolio);

        return $this->portfolioView($portfolio, $portfolio->template, embedded: false);
    }

    // ── Sharing ─────────────────────────────────────────────────────────────

    /** Turn the public link on or off, and optionally change its address. */
    public function share(Request $request, Portfolio $portfolio): RedirectResponse
    {
        Gate::authorize('manage', $portfolio);

        $request->merge(['slug' => Str::slug((string) $request->input('slug'))]);

        $data = $request->validate([
            'is_public' => ['required', 'boolean'],
            'slug' => ['nullable', 'string', 'min:3', 'max:80', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('portfolios', 'slug')->ignore($portfolio->id)],
        ], [
            'slug.unique' => 'That link is already taken. Try adding a number or your middle name.',
            'slug.min' => 'Use at least 3 characters for the link.',
        ]);

        $public = (bool) $data['is_public'];

        if ($public && ! $portfolio->generated_at) {
            return back()->withErrors(['share' => 'Generate your portfolio first, then you can share it.']);
        }

        $portfolio->update([
            'is_public' => $public,
            'slug' => $data['slug'] ?: ($portfolio->slug ?: Portfolio::uniqueSlug($portfolio->full_name, $portfolio->id)),
        ]);

        return back()->with('status', $public
            ? 'Sharing is on. Anyone with the link can view this portfolio.'
            : 'Sharing is off. The public link no longer works.');
    }

    /** The public, read-only portfolio page. No login needed. */
    public function showPublic(string $slug): View
    {
        $portfolio = Portfolio::where('slug', $slug)
            ->where('is_public', true)
            ->whereNotNull('generated_at')
            ->firstOrFail();

        $portfolio->load(['educations', 'skills', 'projects', 'experiences', 'links']);

        return view('templates.'.$portfolio->template, [
            'p' => $portfolio,
            'embedded' => false,
            'owner' => false,
            'public' => true,
        ]);
    }

    // ── Delete ──────────────────────────────────────────────────────────────

    public function destroy(Portfolio $portfolio): RedirectResponse
    {
        Gate::authorize('manage', $portfolio);

        $title = $portfolio->title;

        if ($portfolio->photo_path) {
            $this->deletePhoto($portfolio->photo_path);
        }

        $portfolio->delete();

        return redirect()->route('dashboard')->with('status', '“'.$title.'” was deleted.');
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function portfolioView(Portfolio $portfolio, string $template, bool $embedded): View
    {
        $portfolio->load(['educations', 'skills', 'projects', 'experiences', 'links']);

        return view('templates.'.$template, [
            'p' => $portfolio,
            'embedded' => $embedded,
            'owner' => ! $embedded,
        ]);
    }

    private function validatePersonal(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'full_name' => ['required', 'string', 'max:120'],
            'headline' => ['nullable', 'string', 'max:160'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+()\-.\s]{6,40}$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:3000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'remove_photo' => ['nullable', 'boolean'],
        ], [
            'title.required' => 'Give this portfolio a name, e.g. “Web developer portfolio”.',
            'full_name.required' => 'Enter your full name.',
            'phone.regex' => 'Use digits, spaces, and + ( ) - only.',
            'photo.max' => 'That photo is over 5 MB. Choose a smaller image.',
            'photo.image' => 'The profile picture must be a JPG, PNG, or WebP image.',
        ]);
    }

    /** Replace all rows of a repeatable section with the submitted ones. */
    private function saveSection(Request $request, Portfolio $portfolio, string $step): void
    {
        $section = self::SECTIONS[$step];
        $fields = array_keys($section['rules']);

        // Drop rows the user added but left blank, and normalise URLs.
        $items = collect($request->input('items', []))
            ->map(fn ($row) => collect($fields)->mapWithKeys(fn ($field) => [$field => is_array($row) ? ($row[$field] ?? null) : null])->all())
            ->map(function ($row) {
                foreach (['url'] as $field) {
                    if (filled($row[$field] ?? null) && ! Str::startsWith($row[$field], ['http://', 'https://'])) {
                        $row[$field] = 'https://'.ltrim($row[$field], '/');
                    }
                }
                if (array_key_exists('is_current', $row)) {
                    $row['is_current'] = (bool) $row['is_current'];
                }

                return $row;
            })
            ->filter(fn ($row) => collect($row)->except(self::DEFAULTED_FIELDS)->filter(fn ($v) => filled($v))->isNotEmpty())
            ->values()
            ->all();

        $request->merge(['items' => $items]);

        $rules = ['items' => ['array', 'max:30']];
        foreach ($section['rules'] as $field => $fieldRules) {
            $rules['items.*.'.$field] = $fieldRules;
        }

        $validated = $request->validate($rules, [
            'items.*.*.required' => 'This field is required.',
            'items.*.url.url' => 'Enter a full web address, e.g. https://github.com/you.',
        ]);

        DB::transaction(function () use ($portfolio, $section, $validated) {
            $relation = $portfolio->{$section['relation']}();
            $relation->delete();

            foreach ($validated['items'] ?? [] as $position => $row) {
                $relation->create($row + ['position' => $position]);
            }
        });
    }

    private function afterSave(Request $request, Portfolio $portfolio, string $step, string $message): RedirectResponse
    {
        if ($request->input('intent') === 'next') {
            $steps = array_keys(Portfolio::STEPS);
            $next = $steps[array_search($step, $steps) + 1] ?? null;

            return $next
                ? redirect()->route('portfolios.edit', [$portfolio, $next])->with('status', $message)
                : redirect()->route('portfolios.template', $portfolio)->with('status', $message.' Now choose a template.');
        }

        return redirect()->route('portfolios.edit', [$portfolio, $step])->with('status', $message);
    }

    private function storePhoto(UploadedFile $file, Portfolio $portfolio): string
    {
        $path = 'photos/'.$portfolio->user_id.'/'.Str::uuid().'.'.($file->extension() ?: 'jpg');

        // The Supabase bucket is public, so no ACL is sent (its S3 API doesn't support ACLs).
        Storage::disk(config('filesystems.photo_disk'))->put($path, $file->get(), [
            'ContentType' => $file->getMimeType(),
        ]);

        return $path;
    }

    private function deletePhoto(string $path): void
    {
        try {
            Storage::disk(config('filesystems.photo_disk'))->delete($path);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
