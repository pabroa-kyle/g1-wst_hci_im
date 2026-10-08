<?php

namespace App\Support;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Link;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\Skill;

/**
 * Clearly-labelled sample content, used for the home page template previews
 * and for the "Start from sample data" shortcut on the dashboard.
 */
class SamplePortfolio
{
    public static function attributes(): array
    {
        return [
            'title' => 'Sample portfolio',
            'full_name' => 'Andrea Reyes',
            'headline' => 'Front-end developer & UI designer',
            'email' => 'andrea.reyes@example.com',
            'phone' => '+63 917 555 0142',
            'address' => 'Quezon City, Metro Manila',
            'about' => "I design and build friendly, accessible web interfaces. I’m in my final year of BS Information Technology, and I spend my spare time redesigning the apps my family uses every day.\n\nI care about clear forms, readable type, and pages that load fast on slow connections.",
        ];
    }

    public static function sections(): array
    {
        return [
            'educations' => [
                ['school' => 'Northbridge State University', 'degree' => 'BS Information Technology', 'start_year' => '2023', 'end_year' => '2027', 'description' => 'Dean’s lister. Capstone: an offline-first barangay health records app.'],
                ['school' => 'Eastfield Senior High School', 'degree' => 'STEM Strand', 'start_year' => '2021', 'end_year' => '2023', 'description' => 'Graduated with honors.'],
            ],
            'skills' => [
                ['name' => 'HTML & CSS', 'level' => 5],
                ['name' => 'JavaScript', 'level' => 4],
                ['name' => 'Laravel', 'level' => 4],
                ['name' => 'Figma', 'level' => 4],
                ['name' => 'PostgreSQL', 'level' => 3],
                ['name' => 'Accessibility', 'level' => 3],
            ],
            'projects' => [
                ['title' => 'Barangay Health Records', 'role' => 'Lead developer', 'year' => '2026', 'tech' => 'Laravel, PostgreSQL, Tailwind', 'url' => 'https://example.com/health', 'description' => 'Offline-first records app for community health workers. Cut patient lookup time from minutes to seconds.'],
                ['title' => 'Jeepney Route Finder', 'role' => 'Designer & developer', 'year' => '2025', 'tech' => 'JavaScript, Leaflet', 'url' => 'https://example.com/routes', 'description' => 'Map-based route planner for commuters, built during a 48-hour hackathon.'],
                ['title' => 'Campus Org Portal', 'role' => 'UI designer', 'year' => '2024', 'tech' => 'Figma, Blade', 'url' => null, 'description' => 'Redesigned the sign-up flow for 30+ student organisations.'],
            ],
            'experiences' => [
                ['company' => 'Brightline Digital', 'role' => 'Front-end Intern', 'location' => 'Makati City', 'start_date' => 'Jun 2026', 'end_date' => null, 'is_current' => true, 'description' => 'Building Blade and Tailwind components for client dashboards. Shipped an accessible date-picker used across three products.'],
                ['company' => 'NSU IT Help Desk', 'role' => 'Student Assistant', 'location' => 'Quezon City', 'start_date' => 'Aug 2024', 'end_date' => 'May 2026', 'is_current' => false, 'description' => 'Supported 2,000+ students and staff with accounts, Wi-Fi, and lab machines.'],
            ],
            'links' => [
                ['label' => 'GitHub', 'url' => 'https://github.com/'],
                ['label' => 'LinkedIn', 'url' => 'https://www.linkedin.com/'],
                ['label' => 'Website', 'url' => 'https://example.com/'],
            ],
        ];
    }

    /** An unsaved Portfolio with relations set in memory (no database writes). */
    public static function make(string $template = 'simple'): Portfolio
    {
        $portfolio = new Portfolio(self::attributes() + ['template' => $template]);

        $models = [
            'educations' => Education::class,
            'skills' => Skill::class,
            'projects' => Project::class,
            'experiences' => Experience::class,
            'links' => Link::class,
        ];

        foreach (self::sections() as $relation => $rows) {
            $portfolio->setRelation($relation, collect($rows)->map(fn ($row) => new $models[$relation]($row)));
        }

        return $portfolio;
    }

    /** Persist a sample portfolio for a user. */
    public static function createFor($user): Portfolio
    {
        $portfolio = $user->portfolios()->create(self::attributes() + ['template' => 'modern']);

        foreach (self::sections() as $relation => $rows) {
            foreach (array_values($rows) as $i => $row) {
                $portfolio->{$relation}()->create($row + ['position' => $i]);
            }
        }

        return $portfolio;
    }
}
