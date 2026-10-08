{{-- Template 2 · Modern: dark header, then cards, meters, timeline and tiles. --}}
@php
    $photo = $p->photoUrl();
    $dates = fn ($a, $b, $current = false) => trim(($a ?: '').(($a && ($b || $current)) ? ' – ' : '').($current ? 'Present' : ($b ?: '')));
    $levels = [1 => 'Beginner', 2 => 'Basic', 3 => 'Intermediate', 4 => 'Advanced', 5 => 'Expert'];
    $nav = array_filter([
        'about' => $p->about ? 'About' : null,
        'skills' => $p->skills->isNotEmpty() ? 'Skills' : null,
        'projects' => $p->projects->isNotEmpty() ? 'Projects' : null,
        'experience' => $p->experiences->isNotEmpty() ? 'Experience' : null,
        'education' => $p->educations->isNotEmpty() ? 'Education' : null,
        'contact' => 'Contact',
    ]);
    $tileColors = ['bg-[#0f766e]', 'bg-[#4338ca]', 'bg-[#b45309]', 'bg-[#be185d]', 'bg-[#0369a1]', 'bg-[#4d7c0f]'];
@endphp
<x-layouts.portfolio :p="$p" :owner="$owner" body-class="bg-[#eef1f6] font-sora text-[#0b1220] selection:bg-[#2dd4bf] selection:text-[#0b1220]">
    <div class="bg-[#0b1220] text-white">
        <nav class="sticky top-0 z-40 border-b border-white/10 bg-[#0b1220]/85 backdrop-blur" aria-label="Sections">
            <div class="mx-auto flex max-w-6xl items-center justify-between gap-6 px-6 py-4">
                <a href="#top" class="flex size-9 items-center justify-center rounded-xl bg-[#2dd4bf] text-sm font-bold text-[#0b1220]">{{ $p->initials() }}</a>
                <ul class="hidden items-center gap-1 text-sm text-white/70 md:flex">
                    @foreach ($nav as $id => $label)
                        <li><a href="#{{ $id }}" class="rounded-lg px-3 py-2 hover:bg-white/10 hover:text-white">{{ $label }}</a></li>
                    @endforeach
                </ul>
                @if ($p->email)
                    <a href="mailto:{{ $p->email }}" class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-[#0b1220] hover:bg-[#2dd4bf]">Get in touch</a>
                @endif
            </div>
        </nav>

        <header id="top" class="mx-auto grid max-w-6xl gap-12 px-6 pt-16 pb-28 md:grid-cols-[1fr_auto] md:items-center md:pt-24 md:pb-36">
            <div class="min-w-0">
                <h1 class="text-[clamp(2.75rem,6.5vw,4.75rem)] leading-[1] font-semibold tracking-[-0.04em] text-balance">{{ $p->full_name }}</h1>
                @if ($p->headline)
                    <p class="mt-3 text-[clamp(1.125rem,2vw,1.5rem)] font-medium tracking-[-0.01em] text-[#5eead4]">{{ $p->headline }}</p>
                @endif
                @if ($p->about)
                    <p class="mt-6 max-w-[56ch] text-lg leading-relaxed text-white/70">{{ \Illuminate\Support\Str::limit(preg_split('/\n\s*\n/', trim($p->about))[0], 220) }}</p>
                @endif
                <ul class="mt-8 flex flex-wrap gap-2.5 text-sm">
                    @if ($p->email)<li><a href="mailto:{{ $p->email }}" class="inline-flex items-center gap-2 rounded-xl bg-white/8 px-3.5 py-2 text-white/85 ring-1 ring-white/10 hover:bg-white/14"><x-lucide-mail class="size-4 text-[#5eead4]" />{{ $p->email }}</a></li>@endif
                    @if ($p->phone)<li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $p->phone) }}" class="inline-flex items-center gap-2 rounded-xl bg-white/8 px-3.5 py-2 text-white/85 ring-1 ring-white/10 hover:bg-white/14"><x-lucide-phone class="size-4 text-[#5eead4]" />{{ $p->phone }}</a></li>@endif
                    @if ($p->address)<li class="inline-flex items-center gap-2 rounded-xl bg-white/8 px-3.5 py-2 text-white/85 ring-1 ring-white/10"><x-lucide-map-pin class="size-4 text-[#5eead4]" />{{ $p->address }}</li>@endif
                </ul>
            </div>
            <div class="relative mx-auto size-56 md:size-72">
                @if ($photo)
                    <img src="{{ $photo }}" alt="Photo of {{ $p->full_name }}" class="relative size-full rounded-[2rem] object-cover shadow-[0_24px_48px_-16px_rgb(0_0_0/0.55)] ring-1 ring-white/15">
                @else
                    <div class="relative flex size-full items-center justify-center rounded-[2rem] bg-[radial-gradient(circle_at_30%_25%,#1e3a5f,#16213a_70%)] text-7xl font-semibold tracking-[-0.04em] text-[#5eead4] shadow-[0_24px_48px_-16px_rgb(0_0_0/0.55)] ring-1 ring-white/15">{{ $p->initials() }}</div>
                @endif
            </div>
        </header>
    </div>

    <main class="mx-auto -mt-16 max-w-6xl px-6 pb-24">
        @php($card = 'rounded-3xl bg-white p-7 shadow-[0_1px_2px_rgb(11_18_32/0.06),0_12px_32px_-16px_rgb(11_18_32/0.18)] md:p-9')
        @php($h2 = 'flex items-center gap-3 text-xl font-semibold tracking-[-0.02em]')
        @php($badge = 'flex size-9 items-center justify-center rounded-xl bg-[#ccfbf1] text-[#0f766e]')

        <div class="grid gap-6 md:grid-cols-5">
            @if ($p->about)
                <section id="about" class="{{ $card }} scroll-mt-24 md:col-span-3">
                    <h2 class="{{ $h2 }}"><span class="{{ $badge }}"><x-lucide-user-round class="size-[18px]" /></span>About me</h2>
                    <div class="mt-5 space-y-4 leading-[1.75] text-[#334155]">
                        @foreach (preg_split('/\n\s*\n/', trim($p->about)) as $para)
                            <p>{!! nl2br(e($para)) !!}</p>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($p->skills->isNotEmpty())
                <section id="skills" class="{{ $card }} scroll-mt-24 {{ $p->about ? 'md:col-span-2' : 'md:col-span-5' }}">
                    <h2 class="{{ $h2 }}"><span class="{{ $badge }}"><x-lucide-wrench class="size-[18px]" /></span>Skills</h2>
                    <ul class="mt-6 space-y-4">
                        @foreach ($p->skills as $skill)
                            <li>
                                <div class="mb-1.5 flex items-baseline justify-between text-sm">
                                    <span class="font-medium">{{ $skill->name }}</span>
                                    <span class="text-[#64748b]">{{ $levels[$skill->level] ?? '' }}</span>
                                </div>
                                <div class="h-2 rounded-full bg-[#e2e8f0]">
                                    <div class="h-full rounded-full bg-[#14b8a6]" style="width: {{ $skill->level * 20 }}%"></div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>

        @if ($p->projects->isNotEmpty())
            <section id="projects" class="mt-16 scroll-mt-24">
                <div class="mb-6 flex items-end justify-between">
                    <h2 class="text-3xl font-semibold tracking-[-0.03em]">Projects</h2>
                    <p class="text-sm text-[#64748b]">{{ $p->projects->count() }} {{ \Illuminate\Support\Str::plural('project', $p->projects->count()) }}</p>
                </div>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($p->projects as $project)
                        <article class="flex flex-col overflow-hidden rounded-3xl bg-white shadow-[0_1px_2px_rgb(11_18_32/0.06),0_12px_32px_-16px_rgb(11_18_32/0.18)]">
                            <div class="flex min-h-28 flex-col justify-end {{ $tileColors[$loop->index % count($tileColors)] }} p-6 text-white">
                                <h3 class="text-xl leading-tight font-semibold tracking-[-0.02em]">{{ $project->title }}</h3>
                                @if ($project->role || $project->year)
                                    <p class="mt-1 text-sm text-white/85">{{ collect([$project->role, $project->year])->filter()->implode(' · ') }}</p>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-6">
                                @if ($project->description)<p class="mt-3 text-[0.9375rem] leading-relaxed text-[#475569]">{{ $project->description }}</p>@endif
                                @if ($project->tech)
                                    <ul class="mt-4 flex flex-wrap gap-1.5">
                                        @foreach (array_filter(array_map('trim', explode(',', $project->tech))) as $tech)
                                            <li class="rounded-lg bg-[#f1f5f9] px-2.5 py-1 text-xs font-medium text-[#334155]">{{ $tech }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if ($project->url)
                                    <a href="{{ $project->url }}" target="_blank" rel="noopener" class="mt-auto inline-flex items-center gap-1.5 pt-5 text-sm font-semibold text-[#0f766e] hover:underline">View project <x-lucide-arrow-right class="size-4" /></a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <div class="mt-16 grid gap-6 md:grid-cols-5">
            @if ($p->experiences->isNotEmpty())
                <section id="experience" class="{{ $card }} scroll-mt-24 {{ $p->educations->isNotEmpty() ? 'md:col-span-3' : 'md:col-span-5' }}">
                    <h2 class="{{ $h2 }}"><span class="{{ $badge }}"><x-lucide-briefcase class="size-[18px]" /></span>Experience</h2>
                    <ol class="relative mt-7 space-y-8 border-l-2 border-[#e2e8f0] pl-7">
                        @foreach ($p->experiences as $job)
                            <li class="relative">
                                <span class="absolute top-1.5 -left-[2.15rem] size-3.5 rounded-full border-[3px] border-white {{ $job->is_current ? 'bg-[#14b8a6] ring-4 ring-[#ccfbf1]' : 'bg-[#94a3b8]' }}"></span>
                                <p class="text-sm font-medium text-[#64748b]">{{ $dates($job->start_date, $job->end_date, $job->is_current) }}</p>
                                <h3 class="mt-1 text-lg font-semibold">{{ $job->role }}</h3>
                                <p class="text-[#0f766e]">{{ $job->company }}@if($job->location) <span class="text-[#64748b]">· {{ $job->location }}</span>@endif</p>
                                @if ($job->description)<p class="mt-2.5 leading-relaxed text-[#475569]">{{ $job->description }}</p>@endif
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif

            @if ($p->educations->isNotEmpty())
                <section id="education" class="{{ $card }} scroll-mt-24 {{ $p->experiences->isNotEmpty() ? 'md:col-span-2' : 'md:col-span-5' }}">
                    <h2 class="{{ $h2 }}"><span class="{{ $badge }}"><x-lucide-graduation-cap class="size-[18px]" /></span>Education</h2>
                    <div class="mt-6 space-y-4">
                        @foreach ($p->educations as $edu)
                            <article class="rounded-2xl bg-[#f8fafc] p-5 ring-1 ring-[#e2e8f0]">
                                <p class="text-sm font-medium text-[#64748b]">{{ $dates($edu->start_year, $edu->end_year) }}</p>
                                <h3 class="mt-1 font-semibold">{{ $edu->school }}</h3>
                                @if ($edu->degree)<p class="text-sm text-[#0f766e]">{{ $edu->degree }}</p>@endif
                                @if ($edu->description)<p class="mt-2 text-sm leading-relaxed text-[#475569]">{{ $edu->description }}</p>@endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <section id="contact" class="mt-16 scroll-mt-24 overflow-hidden rounded-3xl bg-[#0b1220] p-8 text-white md:p-12">
            <div class="grid gap-10 md:grid-cols-2 md:items-end">
                <div>
                    <h2 class="text-[clamp(2rem,4vw,3rem)] leading-[1.05] font-semibold tracking-[-0.035em]">Let’s work together.</h2>
                    @if ($p->email)
                        <a href="mailto:{{ $p->email }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#2dd4bf] px-5 py-3 font-semibold text-[#0b1220] hover:bg-[#5eead4]"><x-lucide-mail class="size-4" /> {{ $p->email }}</a>
                    @endif
                </div>
                @if ($p->links->isNotEmpty())
                    <ul class="grid gap-2 sm:grid-cols-2">
                        @foreach ($p->links as $link)
                            <li>
                                <a href="{{ $link->url }}" target="_blank" rel="noopener" class="flex items-center gap-3 rounded-xl bg-white/6 px-4 py-3 ring-1 ring-white/10 hover:bg-white/12">
                                    <x-link-icon :label="$link->label.' '.$link->url" class="size-[18px] text-[#5eead4]" />
                                    <span class="font-medium">{{ $link->label }}</span>
                                    <x-lucide-arrow-right class="ml-auto size-4 -rotate-45 text-white/50" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>

        <footer class="mt-10 text-center text-sm text-[#64748b]">© {{ now()->year }} {{ $p->full_name }}</footer>
    </main>
</x-layouts.portfolio>
