@use('App\Models\Portfolio')
@php($createUrl = auth()->check() ? route('portfolios.create') : route('register'))
<x-layouts.base body-class="bg-n-0">
    <header class="border-b border-n-200">
        <div class="mx-auto flex max-w-[90rem] items-center justify-between px-4 py-4 sm:px-6 lg:px-10">
            <a href="{{ route('home') }}" aria-label="Folio home"><x-logo /></a>
            <nav class="flex items-center gap-2" aria-label="Account">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm"><x-lucide-layout-grid /> My portfolios</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Log in</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Create account</a>
                @endauth
            </nav>
        </div>
    </header>

    <main id="main">
        {{-- Thesis --}}
        <section class="mx-auto grid max-w-[90rem] grid-cols-12 gap-x-6 gap-y-8 px-4 pt-12 pb-14 sm:px-6 lg:px-10 lg:pt-20 lg:pb-20">
            <h1 class="col-span-12 font-wide text-[clamp(2.5rem,6.4vw,5.75rem)] leading-[0.95] font-extrabold tracking-[-0.04em] text-balance text-n-950 lg:col-span-8">
                Enter it once. Generate it three&nbsp;ways.
            </h1>
            <div class="col-span-12 flex flex-col justify-end lg:col-span-4">
                <p class="max-w-[46ch] text-lg leading-relaxed text-n-700">
                    <strong class="font-semibold text-n-950">Folio</strong> is an online portfolio template generator. Add your details, education, skills, projects, and experience. Folio saves them to a cloud database. Then pick the Simple, Modern, or Creative template and generate your portfolio page.
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ $createUrl }}" class="btn btn-primary btn-lg">Create portfolio <x-lucide-arrow-right /></a>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">Log in</a>
                    @endguest
                </div>
            </div>
        </section>

        {{-- Applications: the same sample person, three templates --}}
        <section class="relative" aria-labelledby="templates-heading">
            <div class="absolute inset-x-0 top-24 bottom-0 bg-ink-500 lg:top-40"></div>
            <div class="relative mx-auto max-w-[90rem] px-4 pb-14 sm:px-6 lg:px-10 lg:pb-20">
                <h2 id="templates-heading" class="sr-only">The three templates</h2>
                <div class="grid grid-cols-12 gap-6 lg:gap-8">
                    @foreach (Portfolio::TEMPLATES as $key => $tpl)
                        <figure class="col-span-12 md:col-span-4">
                            <x-sheet-frame :src="route('samples.show', $key)" class="aspect-[4/5]" :label="$tpl['name'].' template sample'" />
                            <figcaption class="mt-5 text-white">
                                <div class="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1">
                                    <p class="font-wide text-2xl font-extrabold tracking-[-0.02em]">{{ $tpl['name'] }}</p>
                                    <p class="spec whitespace-nowrap text-ink-200">Template 0{{ $loop->iteration }} · {{ $tpl['face'] }}</p>
                                </div>
                                <p class="mt-2 max-w-[48ch] text-[0.9375rem] leading-relaxed text-ink-100">{{ $tpl['summary'] }}</p>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
                <p class="mt-10 border-t border-ink-400/60 pt-5 text-sm text-ink-100">
                    All three sheets show the same sample person, Andrea Reyes. Your portfolio uses your own information.
                </p>
            </div>
        </section>

        {{-- How it works: the required flow --}}
        <section class="mx-auto max-w-[90rem] px-4 py-16 sm:px-6 lg:px-10 lg:py-24" aria-labelledby="how-heading">
            <div class="grid grid-cols-12 gap-6">
                <h2 id="how-heading" class="col-span-12 font-wide text-[clamp(1.75rem,3vw,2.5rem)] leading-[1.05] font-extrabold tracking-[-0.03em] text-n-950 lg:col-span-4">
                    From blank form to finished page
                </h2>
                <ol class="col-span-12 border-t-2 border-n-950 lg:col-span-8">
                    @foreach ([
                        ['Create a portfolio', 'Make an account and start a new portfolio. You can keep as many as you like.'],
                        ['Enter your information', 'Six short sections: personal, education, skills, projects, experience, and links.'],
                        ['Save it online', 'Every section saves to a cloud PostgreSQL database, so you can finish later.'],
                        ['Choose a template', 'See your own content in Simple, Modern, and Creative side by side.'],
                        ['Generate & preview', 'Generate the page, then check it at desktop, tablet, and phone sizes.'],
                        ['Edit or delete anytime', 'Update any section, switch templates, or delete a portfolio from your dashboard.'],
                    ] as [$title, $text])
                        <li class="grid grid-cols-[2.5rem_1fr] gap-x-4 gap-y-1 border-b border-n-200 py-5 sm:grid-cols-[3rem_14rem_1fr] sm:items-baseline">
                            <span class="font-wide text-sm font-bold text-ink-500 tabular-nums">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3 class="font-semiwide text-base font-bold text-n-950">{{ $title }}</h3>
                            <p class="col-start-2 text-[0.9375rem] leading-relaxed text-n-600 sm:col-start-3">{{ $text }}</p>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>

        {{-- What goes in --}}
        <section class="border-t border-n-200 bg-n-50" aria-labelledby="fields-heading">
            <div class="mx-auto grid max-w-[90rem] grid-cols-12 gap-6 px-4 py-16 sm:px-6 lg:px-10 lg:py-24">
                <div class="col-span-12 lg:col-span-4">
                    <h2 id="fields-heading" class="font-wide text-[clamp(1.75rem,3vw,2.5rem)] leading-[1.05] font-extrabold tracking-[-0.03em] text-n-950">Everything a portfolio needs</h2>
                    <p class="mt-4 max-w-[40ch] leading-relaxed text-n-600">Fill in what you have now. The dashboard shows which sections are still incomplete.</p>
                </div>
                <dl class="col-span-12 grid border-t border-n-300 sm:grid-cols-2 lg:col-span-8">
                    @foreach ([
                        ['Full name & headline', 'Personal'],
                        ['Profile picture', 'Personal'],
                        ['Email & contact number', 'Personal'],
                        ['Address', 'Personal'],
                        ['About me', 'Personal'],
                        ['Educational background', 'Education'],
                        ['Skills with levels', 'Skills'],
                        ['Projects with links', 'Projects'],
                        ['Work experience', 'Experience'],
                        ['Social media & website links', 'Links'],
                    ] as [$field, $section])
                        <div class="flex items-baseline justify-between gap-4 border-b border-n-300 py-3.5 sm:odd:mr-6">
                            <dt class="font-medium text-n-900">{{ $field }}</dt>
                            <dd class="spec text-n-500">{{ $section }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </section>

        <section class="bg-ink-500">
            <div class="mx-auto flex max-w-[90rem] flex-col gap-6 px-4 py-14 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-10 lg:py-16">
                <h2 class="font-wide text-[clamp(1.75rem,3.4vw,2.75rem)] leading-[1.05] font-extrabold tracking-[-0.03em] text-white">Start your portfolio now.</h2>
                <a href="{{ $createUrl }}" class="btn btn-on-ink btn-lg self-start md:self-auto">Create portfolio <x-lucide-arrow-right /></a>
            </div>
        </section>
    </main>

    <footer class="border-t border-n-200">
        <div class="mx-auto flex max-w-[90rem] flex-col gap-3 px-4 py-8 text-sm text-n-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-10">
            <x-logo class="scale-90 origin-left" />
            <p>Built with Laravel, Tailwind CSS, and Supabase · WST · HCI · IM, Group 1</p>
        </div>
    </footer>
</x-layouts.base>
