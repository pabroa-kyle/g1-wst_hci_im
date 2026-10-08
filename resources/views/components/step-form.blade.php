@props(['portfolio', 'step', 'intro' => null])
@use('App\Models\Portfolio')
@php
    $exists = $portfolio->exists;
    $steps = array_keys(Portfolio::STEPS);
    $index = array_search($step, $steps);
    $isLast = $index === count($steps) - 1;
    $values = $exists ? $portfolio->completeness() : array_fill_keys($steps, 0);
    $action = $exists ? route('portfolios.update', [$portfolio, $step]) : route('portfolios.store');
@endphp
<x-layouts.app :title="Portfolio::STEPS[$step]['title']" :active="$exists ? 'portfolio-'.$portfolio->id : 'create'">
    <div class="px-4 pt-8 sm:px-6 lg:px-10 lg:pt-10">
        {{-- Header --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0">
                <nav class="mb-3 flex items-center gap-1.5 text-sm text-n-500" aria-label="Breadcrumb">
                    <a href="{{ route('dashboard') }}" class="hover:text-n-900 hover:underline">Portfolios</a>
                    <x-lucide-chevron-right class="size-3.5" />
                    <span class="truncate text-n-700">{{ $exists ? $portfolio->title : 'New portfolio' }}</span>
                </nav>
                <h1 class="font-wide text-[clamp(1.75rem,3vw,2.5rem)] leading-[1.05] font-extrabold tracking-[-0.03em] text-n-950">
                    {{ Portfolio::STEPS[$step]['title'] }}
                </h1>
            </div>
            @if ($exists)
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('portfolios.preview', $portfolio) }}" class="btn btn-secondary btn-sm"><x-lucide-eye /> Preview</a>
                    <a href="{{ route('portfolios.template', $portfolio) }}" class="btn btn-secondary btn-sm"><x-lucide-palette /> Template</a>
                </div>
            @endif
        </div>

        {{-- Stepper: each segment's fill is that section's exact completeness --}}
        <nav class="mt-8" aria-label="Portfolio sections">
            <p class="mb-2 text-sm font-semibold text-ink-600 sm:hidden">Step {{ $index + 1 }} of 6 · {{ Portfolio::STEPS[$step]['label'] }}</p>
            <ol class="grid grid-cols-6 gap-1.5 sm:gap-2">
                @foreach (Portfolio::STEPS as $key => $meta)
                    @php($current = $key === $step)
                    @php($reachable = $exists || $key === 'personal')
                    <li>
                        <a @if($reachable) href="{{ $exists ? route('portfolios.edit', [$portfolio, $key]) : route('portfolios.create') }}" @else aria-disabled="true" @endif
                           @if($current) aria-current="step" @endif
                           @class([
                               'group block rounded-(--radius-ui) py-2 sm:pt-1 sm:pb-2',
                               'pointer-events-none opacity-45' => ! $reachable,
                           ])>
                            <div class="h-1 overflow-hidden rounded-full {{ $current ? 'bg-ink-200' : 'bg-n-200' }}">
                                <div @class(['h-full rounded-full', 'bg-go-400' => $values[$key] >= 1, 'bg-ink-500' => $values[$key] < 1]) style="width: {{ round($values[$key] * 100, 1) }}%"></div>
                            </div>
                            <span @class([
                                'mt-2 items-center gap-1.5 text-sm max-sm:sr-only sm:flex',
                                'font-bold text-ink-600' => $current,
                                'font-medium text-n-600 group-hover:text-n-900' => ! $current,
                            ])>
                                <span class="tabular-nums text-n-400">{{ $loop->iteration }}</span>
                                {{ $meta['label'] }}
                                @if ($values[$key] >= 1)
                                    <x-lucide-check class="size-3.5 text-go-700" aria-label="complete" />
                                @endif
                            </span>
                        </a>
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>

    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" x-data="dirtyForm" novalidate
          class="px-4 pb-32 sm:px-6 lg:px-10">
        @csrf
        @if ($exists) @method('PUT') @endif

        @if ($errors->any())
            <div class="mt-6 flex items-start gap-3 rounded-(--radius-ui) border border-danger-600/30 bg-danger-50 px-4 py-3 text-sm text-danger-700" role="alert">
                <x-lucide-circle-alert class="mt-px size-[18px] shrink-0" />
                <p><strong class="font-semibold">Nothing was saved.</strong> Fix the {{ $errors->count() === 1 ? 'highlighted field' : $errors->count().' highlighted fields' }} below, then save again.</p>
            </div>
        @endif

        <div class="mt-6 grid grid-cols-12 gap-6 lg:gap-10">
            <div class="col-span-12 xl:col-span-8">
                @if ($intro)
                    <p class="mb-6 max-w-[65ch] leading-relaxed text-n-600">{{ $intro }}</p>
                @endif
                {{ $slot }}
            </div>

            <aside class="col-span-12 hidden xl:col-span-4 xl:block">
                <div class="sticky top-8">
                    @if ($exists)
                        <div class="rounded-(--radius-ui) bg-ink-500 p-5">
                            <x-sheet-frame :src="route('portfolios.render', $portfolio)" class="aspect-[4/5]" :label="'Current '.$portfolio->templateName().' preview'" />
                            <div class="mt-4 flex items-center justify-between gap-3 text-white">
                                <p class="spec text-ink-100">{{ $portfolio->templateName() }} · saved version</p>
                                <a href="{{ route('portfolios.preview', $portfolio) }}" class="text-sm font-semibold underline-offset-4 hover:underline">Open</a>
                            </div>
                        </div>
                        <p class="mt-3 text-[0.8125rem] leading-relaxed text-n-500">The preview shows your last saved version. Save to update it.</p>
                    @else
                        <div class="rounded-(--radius-ui) border border-dashed border-n-300 p-6">
                            <p class="font-semibold text-n-800">Your preview appears here</p>
                            <p class="mt-2 text-sm leading-relaxed text-n-600">After you save this first section, a live preview of your portfolio shows up beside the form.</p>
                        </div>
                    @endif
                </div>
            </aside>
        </div>

        {{-- Save bar --}}
        <div class="fixed inset-x-0 bottom-0 z-30 border-t border-n-200 bg-white/95 backdrop-blur-sm lg:left-[256px]">
            <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:px-10">
                <p class="flex min-w-0 items-center gap-2 text-sm" aria-live="polite">
                    <span x-show="!dirty && !saving" class="flex items-center gap-2 text-n-600">
                        <span class="size-2 shrink-0 rounded-full {{ $exists ? 'bg-go-400' : 'bg-n-300' }}"></span>
                        <span class="sm:hidden">{{ $exists ? 'Saved' : 'Not saved' }}</span>
                        <span class="hidden truncate sm:inline">{{ $exists ? 'All changes saved' : 'Not saved yet' }}</span>
                    </span>
                    <span x-show="dirty" x-cloak class="flex items-center gap-2 font-semibold text-coral-600">
                        <span class="size-2 shrink-0 rounded-full bg-coral-400"></span>
                        <span class="sm:hidden">Unsaved</span>
                        <span class="hidden truncate sm:inline">Unsaved changes</span>
                    </span>
                    <span x-show="saving" x-cloak class="text-n-600">Saving…</span>
                </p>
                <div class="flex shrink-0 gap-2">
                    @if ($index > 0)
                        <a href="{{ route('portfolios.edit', [$portfolio, $steps[$index - 1]]) }}" class="btn btn-ghost hidden sm:inline-flex"><x-lucide-arrow-left /> Back</a>
                    @endif
                    <button type="submit" name="intent" value="stay" class="btn btn-secondary">Save</button>
                    <button type="submit" name="intent" value="next" class="btn btn-primary">
                        {{ $isLast ? 'Save & choose template' : 'Save & continue' }} <x-lucide-arrow-right />
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.app>
