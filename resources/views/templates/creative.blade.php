{{-- Template 3 · Creative: a poster-like split. A tangerine panel holds the name; the work scrolls beside it. --}}
@php
    $photo = $p->photoUrl();
    $dates = fn ($a, $b, $current = false) => trim(($a ?: '').(($a && ($b || $current)) ? ' – ' : '').($current ? 'Now' : ($b ?: '')));
    $nameParts = preg_split('/\s+/', trim($p->full_name));
    $paras = $p->about ? preg_split('/\n\s*\n/', trim($p->about)) : [];
    $first = $paras ? preg_split('/(?<=[.!?])\s+/', $paras[0], 2) : [];
@endphp
<x-layouts.portfolio :p="$p" :owner="$owner" body-class="bg-[#f7f6f2] font-karla text-[#111] selection:bg-[#d4ff3f] selection:text-[#111]">
    <div class="lg:grid lg:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">
        {{-- Poster panel --}}
        <header class="relative flex flex-col overflow-hidden bg-[#ff5b1f] px-6 pt-8 pb-10 text-[#111] sm:px-10 lg:sticky lg:top-0 lg:h-screen lg:px-12 lg:pt-10">
            <div class="flex items-start justify-between gap-6 font-unbounded text-[0.6875rem] font-medium tracking-[0.04em] uppercase">
                <span>Portfolio<br>{{ now()->year }}</span>
                @if ($p->address)<span class="text-right">Based in<br>{{ $p->address }}</span>@endif
            </div>

            {{-- Name sized from the panel's own width so long names never split mid-word --}}
            @php($longest = max(array_map('mb_strlen', $nameParts ?: [''])) ?: 1)
            <div class="@container mt-10 flex-1 lg:mt-0 lg:flex lg:flex-col lg:justify-center">
                <h1 class="font-unbounded leading-[0.88] font-extrabold tracking-[-0.05em] uppercase"
                    style="font-size: min({{ round(108 / max($longest, 4), 2) }}cqi, 6.5rem)">
                    @foreach ($nameParts as $part)
                        <span class="block {{ $loop->odd ? '' : 'pl-[0.5em]' }}">{{ $part }}</span>
                    @endforeach
                </h1>
                @if ($p->headline)
                    <p class="mt-6 inline-block -rotate-2 self-start bg-[#111] px-4 py-2 font-unbounded text-sm font-semibold text-[#d4ff3f] sm:text-base">{{ $p->headline }}</p>
                @endif
            </div>

            <div class="mt-10 flex items-end justify-between gap-6">
                <ul class="space-y-1 text-[0.9375rem] font-semibold">
                    @if ($p->email)<li><a href="mailto:{{ $p->email }}" class="group inline-flex items-center gap-2 hover:underline"><x-lucide-arrow-right class="size-4 -rotate-45 transition-transform group-hover:rotate-0" />{{ $p->email }}</a></li>@endif
                    @if ($p->phone)<li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $p->phone) }}" class="group inline-flex items-center gap-2 hover:underline"><x-lucide-arrow-right class="size-4 -rotate-45 transition-transform group-hover:rotate-0" />{{ $p->phone }}</a></li>@endif
                </ul>
                <figure class="relative w-32 shrink-0 rotate-[5deg] border-[3px] border-[#111] bg-[#f7f6f2] p-1.5 pb-6 shadow-[8px_8px_0_#111] sm:w-40">
                    @if ($photo)
                        <img src="{{ $photo }}" alt="Photo of {{ $p->full_name }}" class="aspect-square w-full object-cover">
                    @else
                        <div class="flex aspect-square w-full items-center justify-center bg-[#d4ff3f] font-unbounded text-4xl font-extrabold">{{ $p->initials() }}</div>
                    @endif
                    <figcaption class="absolute bottom-1.5 left-2 font-unbounded text-[0.5625rem] font-semibold uppercase">{{ $nameParts[0] ?? '' }}, {{ now()->year }}</figcaption>
                </figure>
            </div>
        </header>

        <main class="min-w-0">
            @if ($paras)
                <section class="px-6 py-16 sm:px-10 lg:px-16 lg:py-24">
                    <h2 class="font-unbounded text-[0.75rem] font-semibold tracking-[0.06em] uppercase">(Hello)</h2>
                    <p class="mt-6 text-[clamp(1.5rem,2.6vw,2.25rem)] leading-[1.25] font-medium tracking-[-0.01em]">
                        <mark class="bg-[#d4ff3f] box-decoration-clone px-1 text-inherit">{{ $first[0] }}</mark>
                        {{ $first[1] ?? '' }}
                    </p>
                    @foreach (array_slice($paras, 1) as $para)
                        <p class="mt-5 max-w-[60ch] text-lg leading-relaxed text-[#3a3a3a]">{{ $para }}</p>
                    @endforeach
                </section>
            @endif

            @if ($p->skills->isNotEmpty())
                @php($ticker = $p->skills->pluck('name')->all())
                <section aria-label="Skills" class="overflow-hidden border-y-[3px] border-[#111] bg-[#111] py-5 text-[#d4ff3f]">
                    <div class="flex w-max animate-ticker">
                        @foreach ([0, 1] as $copy)
                            <ul class="flex shrink-0 items-center" @if($copy) aria-hidden="true" @endif>
                                @foreach (array_merge($ticker, $ticker) as $skill)
                                    <li class="flex items-center font-unbounded text-[clamp(1.5rem,3vw,2.5rem)] font-bold tracking-[-0.03em] whitespace-nowrap uppercase">
                                        <span class="px-6">{{ $skill }}</span>
                                        <svg viewBox="0 0 20 20" class="size-5 shrink-0 fill-[#ff5b1f]" aria-hidden="true"><path d="M10 0l2.4 7.6L20 10l-7.6 2.4L10 20l-2.4-7.6L0 10l7.6-2.4z"/></svg>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </section>
                <ul class="flex flex-wrap gap-2 px-6 pt-8 sm:px-10 lg:px-16" aria-label="Skill levels">
                    @foreach ($p->skills as $skill)
                        <li class="flex items-center gap-2 rounded-full border-2 border-[#111] py-1 pr-3 pl-1 text-sm font-semibold">
                            <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-[#111] px-1.5 text-[0.75rem] text-[#d4ff3f]">{{ $skill->level }}/5</span>
                            {{ $skill->name }}
                        </li>
                    @endforeach
                </ul>
            @endif

            @if ($p->projects->isNotEmpty())
                <section class="px-6 py-16 sm:px-10 lg:px-16 lg:py-24">
                    <h2 class="font-unbounded text-[clamp(2.25rem,4.4vw,4rem)] leading-[0.95] font-extrabold tracking-[-0.045em] uppercase">Selected<br><span class="text-outline">work</span></h2>
                    <ol class="mt-10 border-t-[3px] border-[#111]">
                        @foreach ($p->projects as $project)
                            <li class="group grid gap-x-8 gap-y-3 border-b-[3px] border-[#111] py-8 transition-colors duration-200 hover:bg-[#d4ff3f] sm:grid-cols-[5.5rem_1fr] sm:px-3">
                                <span class="font-unbounded text-5xl leading-none font-extrabold tracking-[-0.05em] text-outline group-hover:text-[#111]">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <div>
                                    <div class="flex flex-wrap items-baseline justify-between gap-x-6 gap-y-1">
                                        <h3 class="font-unbounded text-xl font-bold tracking-[-0.02em] sm:text-2xl">{{ $project->title }}</h3>
                                        <p class="text-sm font-semibold">{{ collect([$project->role, $project->year])->filter()->implode(' · ') }}</p>
                                    </div>
                                    @if ($project->description)<p class="mt-3 max-w-[62ch] text-[1.0625rem] leading-relaxed text-[#2b2b2b]">{{ $project->description }}</p>@endif
                                    <div class="mt-4 flex flex-wrap items-center gap-2">
                                        @foreach (array_filter(array_map('trim', explode(',', (string) $project->tech))) as $tech)
                                            <span class="border-2 border-[#111] px-2 py-0.5 text-[0.8125rem] font-bold uppercase">{{ $tech }}</span>
                                        @endforeach
                                        @if ($project->url)
                                            <a href="{{ $project->url }}" target="_blank" rel="noopener" class="ml-auto inline-flex items-center gap-1.5 bg-[#111] px-3 py-1.5 text-sm font-bold text-[#f7f6f2] hover:bg-[#ff5b1f] hover:text-[#111]">Open <x-lucide-arrow-right class="size-4 -rotate-45" /></a>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif

            @if ($p->experiences->isNotEmpty() || $p->educations->isNotEmpty())
                <section class="grid gap-14 bg-[#111] px-6 py-16 text-[#f7f6f2] sm:px-10 lg:px-16 lg:py-24 xl:grid-cols-2">
                    @if ($p->experiences->isNotEmpty())
                        <div>
                            <h2 class="font-unbounded text-3xl font-extrabold tracking-[-0.04em] uppercase">Jobs</h2>
                            <ol class="mt-8 space-y-8">
                                @foreach ($p->experiences as $job)
                                    <li class="grid grid-cols-[auto_1fr] gap-x-5">
                                        <span class="mt-1.5 size-3 rotate-45 {{ $job->is_current ? 'bg-[#d4ff3f]' : 'bg-[#ff5b1f]' }}"></span>
                                        <div>
                                            <p class="font-unbounded text-[0.75rem] font-medium tracking-[0.04em] text-[#d4ff3f] uppercase">{{ $dates($job->start_date, $job->end_date, $job->is_current) }}</p>
                                            <h3 class="mt-1.5 text-xl font-bold">{{ $job->role }}</h3>
                                            <p class="text-[#b9b9b3]">{{ $job->company }}@if($job->location), {{ $job->location }}@endif</p>
                                            @if ($job->description)<p class="mt-2.5 leading-relaxed text-[#dcdcd6]">{{ $job->description }}</p>@endif
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                    @if ($p->educations->isNotEmpty())
                        <div>
                            <h2 class="font-unbounded text-3xl font-extrabold tracking-[-0.04em] uppercase">School</h2>
                            <ol class="mt-8 space-y-6">
                                @foreach ($p->educations as $edu)
                                    <li class="grid grid-cols-[auto_1fr] gap-x-5">
                                        <span class="mt-1.5 size-3 rounded-full bg-[#ff5b1f]"></span>
                                        <div>
                                            <p class="font-unbounded text-[0.75rem] font-medium tracking-[0.04em] text-[#ff8a5c] uppercase">{{ $dates($edu->start_year, $edu->end_year) }}</p>
                                            <h3 class="mt-1.5 text-xl font-bold">{{ $edu->school }}</h3>
                                            @if ($edu->degree)<p class="text-[#b9b9b3]">{{ $edu->degree }}</p>@endif
                                            @if ($edu->description)<p class="mt-2 leading-relaxed text-[#dcdcd6]">{{ $edu->description }}</p>@endif
                                        </div>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    @endif
                </section>
            @endif

            @if ($p->links->isNotEmpty())
                <section class="px-6 py-16 sm:px-10 lg:px-16 lg:py-24">
                    <h2 class="font-unbounded text-[0.75rem] font-semibold tracking-[0.06em] uppercase">(Find me)</h2>
                    <ul class="mt-6">
                        @foreach ($p->links as $link)
                            <li class="border-b-[3px] border-[#111]">
                                <a href="{{ $link->url }}" target="_blank" rel="noopener" class="group flex items-center justify-between gap-6 py-4 font-unbounded text-[clamp(1.75rem,4vw,3.25rem)] font-extrabold tracking-[-0.045em] uppercase hover:text-[#ff5b1f]">
                                    {{ $link->label }}
                                    <x-lucide-arrow-right class="size-[0.8em] shrink-0 -rotate-45 transition-transform duration-300 group-hover:rotate-0" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <footer class="flex flex-wrap justify-between gap-3 border-t-[3px] border-[#111] px-6 py-6 font-unbounded text-[0.6875rem] font-semibold uppercase sm:px-10 lg:px-16">
                <span>© {{ now()->year }} {{ $p->full_name }}</span>
                @if ($p->email)<a href="mailto:{{ $p->email }}" class="hover:text-[#ff5b1f]">Say hello</a>@endif
            </footer>
        </main>
    </div>
</x-layouts.portfolio>
