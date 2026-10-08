@use('App\Models\Portfolio')
@php($isSaved = $template === $portfolio->template)
<x-layouts.app :title="'Preview: '.$portfolio->title" :active="'portfolio-'.$portfolio->id">
    <div class="flex min-h-[calc(100dvh-3.5rem)] flex-col lg:h-dvh lg:min-h-0" x-data="previewStage">
        <div class="border-b border-n-200 bg-white px-4 pt-6 pb-4 sm:px-6 lg:px-10">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div class="min-w-0">
                    <nav class="mb-2 flex items-center gap-1.5 text-sm text-n-500" aria-label="Breadcrumb">
                        <a href="{{ route('dashboard') }}" class="hover:text-n-900 hover:underline">Portfolios</a>
                        <x-lucide-chevron-right class="size-3.5" />
                        <span class="text-n-700">Preview</span>
                    </nav>
                    <h1 class="truncate font-wide text-[clamp(1.5rem,2.4vw,2rem)] leading-tight font-extrabold tracking-[-0.03em] text-n-950">{{ $portfolio->title }}</h1>
                    <p class="mt-1 text-sm text-n-600">
                        @if ($portfolio->generated_at)
                            Generated with <strong class="font-semibold text-n-800">{{ $portfolio->templateName() }}</strong> · {{ $portfolio->generated_at->diffForHumans() }}
                        @else
                            Not generated yet. Choose a template and generate to finish.
                        @endif
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('portfolios.edit', $portfolio) }}" class="btn btn-secondary btn-sm"><x-lucide-pencil /> Edit</a>
                    <a href="{{ route('portfolios.template', $portfolio) }}" class="btn btn-secondary btn-sm"><x-lucide-palette /> Change template</a>
                    <a href="{{ route('portfolios.show', $portfolio) }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm"><x-lucide-external-link /> Open full page</a>
                    <button type="button" class="btn btn-ghost btn-sm text-danger-600 hover:bg-danger-50 hover:text-danger-700" onclick="document.getElementById('delete-{{ $portfolio->id }}').showModal()"><x-lucide-trash-2 /> Delete</button>
                </div>
            </div>

            <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="inline-flex self-start rounded-(--radius-ui) bg-n-100 p-1" role="group" aria-label="Template">
                    @foreach (Portfolio::TEMPLATES as $key => $tpl)
                        <a href="{{ route('portfolios.preview', $portfolio) }}?template={{ $key }}" @if($key === $template) aria-current="page" @endif
                           @class([
                               'rounded-[3px] px-3.5 py-1.5 text-sm font-semibold transition-colors duration-150',
                               'bg-white text-ink-600 shadow-[0_1px_2px_rgb(13_15_26/0.12)]' => $key === $template,
                               'text-n-600 hover:text-n-900' => $key !== $template,
                           ])>{{ $tpl['name'] }}</a>
                    @endforeach
                </div>
                <div class="inline-flex self-start rounded-(--radius-ui) bg-n-100 p-1" role="group" aria-label="Screen size">
                    @foreach (['desktop' => ['monitor', 'Desktop'], 'tablet' => ['tablet', 'Tablet'], 'phone' => ['smartphone', 'Phone']] as $device => [$icon, $label])
                        <button type="button" @click="device = '{{ $device }}'" :aria-pressed="device === '{{ $device }}'"
                                class="flex items-center gap-1.5 rounded-[3px] px-3 py-1.5 text-sm font-semibold transition-colors duration-150"
                                :class="device === '{{ $device }}' ? 'bg-white text-ink-600 shadow-[0_1px_2px_rgb(13_15_26/0.12)]' : 'text-n-600 hover:text-n-900'">
                            <x-dynamic-component :component="'lucide-'.$icon" class="size-4" /> <span class="hidden sm:inline">{{ $label }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <x-share-panel :portfolio="$portfolio" />

        @if (! $isSaved || ! $portfolio->generated_at)
            <form method="POST" action="{{ route('portfolios.generate', $portfolio) }}"
                  class="flex flex-col gap-3 border-b border-ink-200 bg-ink-50 px-4 py-3 text-sm text-ink-800 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-10">
                @csrf
                <input type="hidden" name="template" value="{{ $template }}">
                @if ($isSaved)
                    <p>This portfolio isn’t generated yet. Happy with <strong>{{ Portfolio::TEMPLATES[$template]['name'] }}</strong>? Generate it to finish.</p>
                @else
                    <p>You’re previewing <strong>{{ Portfolio::TEMPLATES[$template]['name'] }}</strong>. Your portfolio currently uses <strong>{{ $portfolio->templateName() }}</strong>.</p>
                @endif
                <button type="submit" class="btn btn-primary btn-sm self-start"><x-lucide-sparkles /> Generate with {{ Portfolio::TEMPLATES[$template]['name'] }}</button>
            </form>
        @endif

        <div class="relative flex-1 overflow-hidden bg-n-100 p-4 sm:p-6 lg:p-8">
            <div x-ref="stage" class="absolute inset-4 flex justify-center sm:inset-6 lg:inset-8">
                <div x-ref="holder" class="sheet h-full shrink-0">
                    <iframe x-ref="frame" src="{{ route('portfolios.render', $portfolio) }}?template={{ $template }}"
                            title="{{ Portfolio::TEMPLATES[$template]['name'] }} preview of {{ $portfolio->title }}"
                            class="absolute top-0 left-0 origin-top-left border-0 bg-white" style="width: 1280px; height: 100%"></iframe>
                </div>
            </div>
            <div class="min-h-[70dvh] lg:min-h-0"></div>
        </div>
    </div>
    <x-delete-dialog :portfolio="$portfolio" />
</x-layouts.app>
