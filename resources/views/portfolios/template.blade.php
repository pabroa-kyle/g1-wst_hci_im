@use('App\Models\Portfolio')
<x-layouts.app title="Choose a template" :active="'portfolio-'.$portfolio->id">
    <form method="POST" action="{{ route('portfolios.generate', $portfolio) }}" x-data="{ chosen: @js($portfolio->template) }">
        @csrf
        <div class="px-4 pt-8 sm:px-6 lg:px-10 lg:pt-10">
            <nav class="mb-3 flex items-center gap-1.5 text-sm text-n-500" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="hover:text-n-900 hover:underline">Portfolios</a>
                <x-lucide-chevron-right class="size-3.5" />
                <a href="{{ route('portfolios.edit', $portfolio) }}" class="truncate hover:text-n-900 hover:underline">{{ $portfolio->title }}</a>
                <x-lucide-chevron-right class="size-3.5" />
                <span class="text-n-700">Template</span>
            </nav>
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <h1 class="font-wide text-[clamp(1.75rem,3vw,2.5rem)] leading-[1.05] font-extrabold tracking-[-0.03em] text-n-950">Choose a template</h1>
                    <p class="mt-3 max-w-[62ch] leading-relaxed text-n-600">
                        Each template below is showing your saved information. Pick one and generate your portfolio. You can switch anytime without losing anything.
                    </p>
                </div>
                <a href="{{ route('portfolios.edit', $portfolio) }}" class="btn btn-secondary btn-sm self-start md:self-auto"><x-lucide-pencil /> Edit information</a>
            </div>
        </div>

        {{-- Applications spread: one identity, applied three ways --}}
        <fieldset class="mt-8 bg-ink-500 px-4 py-8 sm:px-6 lg:px-10 lg:py-12">
            <legend class="sr-only">Portfolio template</legend>
            <div class="grid grid-cols-12 gap-6 lg:gap-8">
                @foreach (Portfolio::TEMPLATES as $key => $tpl)
                    <label class="group col-span-12 cursor-pointer md:col-span-4">
                        <input type="radio" name="template" value="{{ $key }}" x-model="chosen" class="peer sr-only">
                        <div class="relative rounded-[3px] p-1.5 outline-3 outline-offset-0 transition-[outline-color,transform] duration-200 ease-(--ease-out-expo) peer-focus-visible:ring-4 peer-focus-visible:ring-ink-200 peer-focus-visible:ring-offset-4 peer-focus-visible:ring-offset-ink-500"
                             :class="chosen === '{{ $key }}' ? 'outline-white' : 'outline-transparent group-hover:-translate-y-1'">
                            <x-sheet-frame :src="route('portfolios.render', $portfolio).'?template='.$key" class="aspect-[3/4]" :label="$tpl['name'].' template with your information'" />
                            <span x-show="chosen === '{{ $key }}'" x-cloak x-transition.scale.origin.center
                                  class="absolute top-4 right-4 flex size-9 items-center justify-center rounded-full bg-white text-ink-600 shadow-[0_6px_16px_-6px_rgb(10_17_69/0.6)]">
                                <x-lucide-check class="size-5" />
                            </span>
                        </div>
                        <div class="mt-4 px-1.5 text-white">
                            <div class="flex flex-wrap items-baseline justify-between gap-x-3 gap-y-1">
                                <p class="font-wide text-xl font-extrabold tracking-[-0.01em]">{{ $tpl['name'] }}</p>
                                <p class="spec whitespace-nowrap text-ink-200">Template 0{{ $loop->iteration }} · {{ $tpl['face'] }}</p>
                            </div>
                            <p class="mt-1.5 text-sm leading-relaxed text-ink-100">{{ $tpl['summary'] }}</p>
                            <a href="{{ route('portfolios.preview', $portfolio) }}?template={{ $key }}" class="mt-2 inline-flex items-center gap-1 text-sm font-semibold text-white underline-offset-4 hover:underline" @click.stop>
                                Full preview <x-lucide-arrow-right class="size-3.5" />
                            </a>
                        </div>
                    </label>
                @endforeach
            </div>
        </fieldset>

        <div class="sticky bottom-0 z-20 border-t border-n-200 bg-white/95 backdrop-blur-sm">
            <div class="flex flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-10">
                <p class="text-sm text-n-600">
                    Selected: <strong class="font-semibold text-n-950" x-text="{{ \Illuminate\Support\Js::from(collect(Portfolio::TEMPLATES)->map->name) }}[chosen]">{{ $portfolio->templateName() }}</strong>
                    @if ($portfolio->generated_at)
                        <span class="text-n-500"> · last generated {{ $portfolio->generated_at->diffForHumans() }}</span>
                    @endif
                </p>
                <button type="submit" class="btn btn-primary btn-lg"><x-lucide-sparkles /> Generate portfolio</button>
            </div>
        </div>
    </form>
</x-layouts.app>
