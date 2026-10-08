<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    /** The six sections of the stepped form, in order. */
    public const STEPS = [
        'personal' => ['label' => 'Personal', 'title' => 'Personal information'],
        'education' => ['label' => 'Education', 'title' => 'Educational background'],
        'skills' => ['label' => 'Skills', 'title' => 'Skills'],
        'projects' => ['label' => 'Projects', 'title' => 'Projects'],
        'experience' => ['label' => 'Experience', 'title' => 'Work experience'],
        'links' => ['label' => 'Links', 'title' => 'Social media & website links'],
    ];

    /** Exactly three templates. */
    public const TEMPLATES = [
        'simple' => [
            'name' => 'Simple',
            'face' => 'Source Serif 4',
            'summary' => 'Clean and professional. A single serif column that reads like a well-set résumé.',
        ],
        'modern' => [
            'name' => 'Modern',
            'face' => 'Sora',
            'summary' => 'Cards, sections and visual elements on a dark header, with skill meters and project tiles.',
        ],
        'creative' => [
            'name' => 'Creative',
            'face' => 'Unbounded + Karla',
            'summary' => 'A poster-like split layout with oversized type, a skills ticker and bold colour blocks.',
        ],
    ];

    /** Personal fields that count toward the "Personal" segment. */
    private const PERSONAL_FIELDS = ['full_name', 'headline', 'email', 'phone', 'address', 'about', 'photo_path'];

    protected $fillable = [
        'title', 'template', 'full_name', 'headline', 'email', 'phone', 'address', 'about', 'photo_path', 'generated_at',
    ];

    protected function casts(): array
    {
        return ['generated_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class)->orderBy('position');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('position');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->orderBy('position');
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class)->orderBy('position');
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class)->orderBy('position');
    }

    public function photoUrl(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        try {
            return Storage::disk(config('filesystems.photo_disk'))->url($this->photo_path);
        } catch (\Throwable) {
            return null;
        }
    }

    public function initials(): string
    {
        return Str::of($this->full_name)->explode(' ')->filter()->take(2)
            ->map(fn ($part) => Str::upper(Str::substr($part, 0, 1)))->implode('');
    }

    public function templateName(): string
    {
        return self::TEMPLATES[$this->template]['name'] ?? 'Simple';
    }

    /**
     * Completeness per section, each a fraction from 0 to 1. Segment lengths on
     * the dashboard are drawn from these exact values.
     *
     * @return array<string, float>
     */
    public function completeness(): array
    {
        $filled = collect(self::PERSONAL_FIELDS)->filter(fn ($field) => filled($this->{$field}))->count();

        $count = fn (string $relation) => $this->relationLoaded($relation)
            ? $this->{$relation}->count()
            : ($this->{$relation.'_count'} ?? $this->{$relation}()->count());

        return [
            'personal' => $filled / count(self::PERSONAL_FIELDS),
            'education' => min($count('educations'), 1),
            'skills' => min($count('skills') / 3, 1),
            'projects' => min($count('projects'), 1),
            'experience' => min($count('experiences'), 1),
            'links' => min($count('links'), 1),
        ];
    }

    public function completionPercent(): int
    {
        $values = $this->completeness();

        return (int) round(array_sum($values) / count($values) * 100);
    }

    /** The first section that is not fully complete, for "Continue" links. */
    public function nextIncompleteStep(): ?string
    {
        foreach ($this->completeness() as $step => $value) {
            if ($value < 1) {
                return $step;
            }
        }

        return null;
    }
}
