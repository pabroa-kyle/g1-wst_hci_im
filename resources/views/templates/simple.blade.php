{{-- Template 1 · Simple: a single serif column, set like a careful résumé. --}}
@php
    $photo = $p->photoUrl();
    $contact = array_filter([$p->email, $p->phone, $p->address]);
    $dates = fn ($a, $b, $current = false) => trim(($a ?: '').(($a && ($b || $current)) ? ' – ' : '').($current ? 'Present' : ($b ?: '')));
@endphp
<x-layouts.portfolio :p="$p" :owner="$owner" body-class="bg-white font-serif text-[#1b1b1f] selection:bg-[#1d3a6b] selection:text-white">
    <main class="mx-auto max-w-[48rem] px-6 pt-16 pb-24 sm:px-10 sm:pt-24">
        <header class="flex flex-col-reverse gap-8 border-b border-[#1b1b1f] pb-10 sm:flex-row sm:items-end sm:justify-between">
            <div class="min-w-0">
                <h1 class="text-[clamp(2.5rem,6vw,3.75rem)] leading-[1.02] font-semibold tracking-[-0.025em]">{{ $p->full_name }}</h1>
                @if ($p->headline)
                    <p class="mt-3 text-xl text-[#4a4a55]">{{ $p->headline }}</p>
                @endif
                @if ($contact)
                    <ul class="mt-6 flex flex-wrap gap-x-5 gap-y-1.5 text-[0.9375rem] text-[#4a4a55]">
                        @if ($p->email)<li><a href="mailto:{{ $p->email }}" class="hover:text-[#1d3a6b] hover:underline">{{ $p->email }}</a></li>@endif
                        @if ($p->phone)<li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $p->phone) }}" class="hover:text-[#1d3a6b] hover:underline">{{ $p->phone }}</a></li>@endif
                        @if ($p->address)<li>{{ $p->address }}</li>@endif
                    </ul>
                @endif
            </div>
            @if ($photo)
                <img src="{{ $photo }}" alt="Photo of {{ $p->full_name }}" class="size-28 shrink-0 rounded-full object-cover sm:size-32">
            @endif
        </header>

        @php($label = 'text-[0.75rem] font-semibold tracking-[0.16em] uppercase text-[#1d3a6b] sm:pt-1.5')

        @if ($p->about)
            <section class="grid gap-3 border-b border-[#dcdce2] py-10 sm:grid-cols-[10rem_1fr] sm:gap-8">
                <h2 class="{{ $label }}">About</h2>
                <div class="space-y-4 text-[1.0625rem] leading-[1.7]">
                    @foreach (preg_split('/\n\s*\n/', trim($p->about)) as $para)
                        <p>{!! nl2br(e($para)) !!}</p>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($p->experiences->isNotEmpty())
            <section class="grid gap-3 border-b border-[#dcdce2] py-10 sm:grid-cols-[10rem_1fr] sm:gap-8">
                <h2 class="{{ $label }}">Experience</h2>
                <div class="space-y-8">
                    @foreach ($p->experiences as $job)
                        <article>
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between sm:gap-6">
                                <h3 class="text-lg font-semibold">{{ $job->role }}</h3>
                                <p class="shrink-0 text-[0.9375rem] text-[#6b6b76] tabular-nums">{{ $dates($job->start_date, $job->end_date, $job->is_current) }}</p>
                            </div>
                            <p class="text-[#4a4a55]">{{ $job->company }}@if($job->location), {{ $job->location }}@endif</p>
                            @if ($job->description)
                                <p class="mt-2.5 leading-[1.7]">{{ $job->description }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($p->projects->isNotEmpty())
            <section class="grid gap-3 border-b border-[#dcdce2] py-10 sm:grid-cols-[10rem_1fr] sm:gap-8">
                <h2 class="{{ $label }}">Projects</h2>
                <div class="space-y-8">
                    @foreach ($p->projects as $project)
                        <article>
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between sm:gap-6">
                                <h3 class="text-lg font-semibold">
                                    @if ($project->url)
                                        <a href="{{ $project->url }}" target="_blank" rel="noopener" class="underline decoration-[#1d3a6b]/30 hover:decoration-[#1d3a6b]">{{ $project->title }}</a>
                                    @else
                                        {{ $project->title }}
                                    @endif
                                </h3>
                                @if ($project->year)<p class="shrink-0 text-[0.9375rem] text-[#6b6b76] tabular-nums">{{ $project->year }}</p>@endif
                            </div>
                            @if ($project->role)<p class="text-[#4a4a55]">{{ $project->role }}</p>@endif
                            @if ($project->description)<p class="mt-2.5 leading-[1.7]">{{ $project->description }}</p>@endif
                            @if ($project->tech)<p class="mt-2 text-[0.875rem] text-[#6b6b76]">{{ $project->tech }}</p>@endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($p->educations->isNotEmpty())
            <section class="grid gap-3 border-b border-[#dcdce2] py-10 sm:grid-cols-[10rem_1fr] sm:gap-8">
                <h2 class="{{ $label }}">Education</h2>
                <div class="space-y-6">
                    @foreach ($p->educations as $edu)
                        <article>
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-baseline sm:justify-between sm:gap-6">
                                <h3 class="text-lg font-semibold">{{ $edu->school }}</h3>
                                <p class="shrink-0 text-[0.9375rem] text-[#6b6b76] tabular-nums">{{ $dates($edu->start_year, $edu->end_year) }}</p>
                            </div>
                            @if ($edu->degree)<p class="text-[#4a4a55]">{{ $edu->degree }}</p>@endif
                            @if ($edu->description)<p class="mt-2 leading-[1.7]">{{ $edu->description }}</p>@endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($p->skills->isNotEmpty())
            @php($levels = [1 => 'Beginner', 2 => 'Basic', 3 => 'Intermediate', 4 => 'Advanced', 5 => 'Expert'])
            <section class="grid gap-3 border-b border-[#dcdce2] py-10 sm:grid-cols-[10rem_1fr] sm:gap-8">
                <h2 class="{{ $label }}">Skills</h2>
                <ul class="grid gap-x-8 gap-y-2.5 sm:grid-cols-2">
                    @foreach ($p->skills as $skill)
                        <li class="flex items-baseline justify-between gap-4 border-b border-dotted border-[#c4c4cc] pb-2">
                            <span>{{ $skill->name }}</span>
                            <span class="text-[0.875rem] text-[#6b6b76]">{{ $levels[$skill->level] ?? '' }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($p->links->isNotEmpty())
            <section class="grid gap-3 py-10 sm:grid-cols-[10rem_1fr] sm:gap-8">
                <h2 class="{{ $label }}">Links</h2>
                <ul class="space-y-2">
                    @foreach ($p->links as $link)
                        <li class="flex flex-wrap items-baseline gap-x-3">
                            <span class="w-24 shrink-0 text-[#6b6b76]">{{ $link->label }}</span>
                            <a href="{{ $link->url }}" target="_blank" rel="noopener" class="break-all text-[#1d3a6b] underline decoration-[#1d3a6b]/30 hover:decoration-[#1d3a6b]">{{ preg_replace('#^https?://(www\.)?#', '', rtrim($link->url, '/')) }}</a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        <footer class="mt-6 border-t border-[#1b1b1f] pt-5 text-[0.875rem] text-[#6b6b76]">
            © {{ now()->year }} {{ $p->full_name }}
        </footer>
    </main>
</x-layouts.portfolio>
