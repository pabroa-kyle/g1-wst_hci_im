@php($firstName = \Illuminate\Support\Str::of(auth()->user()->name)->before(' '))
<x-layouts.app title="Manage portfolios" active="dashboard">
    <div class="px-4 py-8 sm:px-6 lg:px-10 lg:py-10">
        <header class="flex flex-col gap-6 border-b border-n-200 pb-8 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0">
                <h1 class="font-wide text-[clamp(2rem,4vw,3.25rem)] leading-[1.02] font-extrabold tracking-[-0.03em] text-n-950">
                    {{ $firstName }}’s portfolios
                </h1>
                <p class="mt-3 text-[0.9375rem] text-n-600">
                    @if ($portfolios->isEmpty())
                        Manage, preview, and edit everything you create here.
                    @else
                        {{ $portfolios->count() }} {{ \Illuminate\Support\Str::plural('portfolio', $portfolios->count()) }}
                        · {{ $portfolios->whereNotNull('generated_at')->count() }} generated
                        · last edited {{ $portfolios->first()->updated_at->diffForHumans() }}
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <form method="POST" action="{{ route('portfolios.sample') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary"><x-lucide-wand-sparkles /> Start from sample data</button>
                </form>
                <a href="{{ route('portfolios.create') }}" class="btn btn-primary"><x-lucide-plus /> New portfolio</a>
            </div>
        </header>

        @if ($portfolios->isEmpty())
            <section class="grid grid-cols-12 items-center gap-8 py-12 lg:py-16">
                <div class="col-span-12 md:col-span-5 lg:col-span-4">
                    <div class="relative mx-auto aspect-[3/4] max-w-64">
                        <div class="absolute inset-0 translate-x-6 -translate-y-6 rounded-(--radius-sheet) border border-dashed border-ink-300 bg-ink-50"></div>
                        <div class="absolute inset-0 translate-x-3 -translate-y-3 rounded-(--radius-sheet) border border-dashed border-ink-300 bg-ink-50"></div>
                        <div class="sheet absolute inset-0 flex flex-col gap-3 p-6">
                            <div class="size-12 rounded-full bg-n-100"></div>
                            <div class="h-3 w-3/4 rounded-full bg-n-200"></div>
                            <div class="h-2 w-1/2 rounded-full bg-n-100"></div>
                            <div class="mt-4 space-y-2">
                                <div class="h-2 rounded-full bg-n-100"></div>
                                <div class="h-2 rounded-full bg-n-100"></div>
                                <div class="h-2 w-2/3 rounded-full bg-n-100"></div>
                            </div>
                            <p class="spec mt-auto text-n-400">Blank sheet</p>
                        </div>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-7 lg:col-span-6">
                    <h2 class="font-semiwide text-2xl font-bold tracking-[-0.01em] text-n-950">Create your first portfolio</h2>
                    <p class="mt-3 max-w-[60ch] leading-relaxed text-n-600">
                        Fill in six short sections: personal info, education, skills, projects, experience, and links. Each one saves to the online database as you go. Then choose the Simple, Modern, or Creative template and generate your page.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-2">
                        <a href="{{ route('portfolios.create') }}" class="btn btn-primary btn-lg"><x-lucide-plus /> Create portfolio</a>
                        <form method="POST" action="{{ route('portfolios.sample') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-lg">Try it with sample data</button>
                        </form>
                    </div>
                </div>
            </section>
        @else
            <section class="mt-8 grid grid-cols-12 gap-5 lg:gap-6" aria-label="Your portfolios">
                @foreach ($portfolios as $portfolio)
                    @php($percent = $portfolio->completionPercent())
                    @php($next = $portfolio->nextIncompleteStep())
                    <article class="panel col-span-12 flex flex-col md:col-span-6 2xl:col-span-4">
                        <a href="{{ route('portfolios.preview', $portfolio) }}" class="group block rounded-t-(--radius-ui) border-b border-n-200 bg-n-100 px-6 pt-6 focus-visible:outline-offset-[-2px]" aria-label="Preview {{ $portfolio->title }}">
                            <x-sheet-frame :src="route('portfolios.render', $portfolio)" class="aspect-[16/10] rounded-b-none transition-transform duration-300 ease-(--ease-out-expo) group-hover:-translate-y-1" :label="$portfolio->title.' preview'" />
                        </a>

                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h2 class="truncate font-semiwide text-lg font-bold text-n-950">
                                        <a href="{{ route('portfolios.preview', $portfolio) }}" class="hover:underline">{{ $portfolio->title }}</a>
                                    </h2>
                                    <p class="mt-0.5 truncate text-sm text-n-600">{{ $portfolio->full_name }}@if($portfolio->headline) · {{ $portfolio->headline }}@endif</p>
                                </div>
                                @if ($portfolio->isShared())
                                    <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-go-50 px-2.5 py-1 text-xs font-semibold text-go-700"><x-lucide-link class="size-3.5" /> Shared</span>
                                @elseif ($portfolio->generated_at)
                                    <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-go-50 px-2.5 py-1 text-xs font-semibold text-go-700"><x-lucide-check class="size-3.5" /> Generated</span>
                                @else
                                    <span class="inline-flex shrink-0 items-center rounded-full bg-n-100 px-2.5 py-1 text-xs font-semibold text-n-600">Draft</span>
                                @endif
                            </div>

                            <p class="spec mt-3 text-n-500">{{ $portfolio->templateName() }} template · edited {{ $portfolio->updated_at->diffForHumans() }}</p>

                            @if ($portfolio->isShared())
                                <div class="mt-3 flex items-center gap-1 rounded-(--radius-ui) bg-n-50 py-1 pr-1 pl-2.5 ring-1 ring-n-200" x-data="{ copied: false }">
                                    <x-lucide-link class="size-3.5 shrink-0 text-go-700" aria-hidden="true" />
                                    <a href="{{ $portfolio->publicUrl() }}" target="_blank" rel="noopener" class="min-w-0 flex-1 truncate text-[0.8125rem] font-medium text-ink-600 hover:underline">{{ preg_replace('#^https?://#', '', $portfolio->publicUrl()) }}</a>
                                    <button type="button" class="btn btn-ghost btn-sm shrink-0 px-2 text-xs"
                                            @click="navigator.clipboard.writeText(@js($portfolio->publicUrl())); copied = true; setTimeout(() => copied = false, 2000)"
                                            :aria-label="copied ? 'Link copied' : 'Copy public link'">
                                        <x-lucide-copy x-show="!copied" /><x-lucide-check x-show="copied" x-cloak class="text-go-700" />
                                        <span x-text="copied ? 'Copied' : 'Copy'">Copy</span>
                                    </button>
                                </div>
                            @elseif ($portfolio->generated_at)
                                <a href="{{ route('portfolios.preview', $portfolio) }}#share" class="mt-3 inline-flex items-center gap-1.5 text-[0.8125rem] font-medium text-n-600 hover:text-ink-600 hover:underline">
                                    <x-lucide-link class="size-3.5" /> Not shared · Share this portfolio
                                </a>
                            @endif

                            <div class="mt-4">
                                <div class="mb-2 flex items-baseline justify-between text-sm">
                                    <span class="font-semibold text-n-800">{{ $percent }}% complete</span>
                                    @if ($next)
                                        <a href="{{ route('portfolios.edit', [$portfolio, $next]) }}" class="font-medium text-ink-500 hover:underline">Continue: {{ \App\Models\Portfolio::STEPS[$next]['label'] }}</a>
                                    @endif
                                </div>
                                <x-segments :portfolio="$portfolio" />
                            </div>

                            <div class="mt-5 grid grid-cols-4 gap-1 border-t sm:flex sm:items-center border-n-200 pt-4">
                                <a href="{{ route('portfolios.preview', $portfolio) }}" class="btn btn-ghost btn-sm max-sm:flex-col max-sm:gap-1 max-sm:px-1 max-sm:text-xs"><x-lucide-eye /> View</a>
                                <a href="{{ route('portfolios.edit', $portfolio) }}" class="btn btn-ghost btn-sm max-sm:flex-col max-sm:gap-1 max-sm:px-1 max-sm:text-xs"><x-lucide-pencil /> Edit</a>
                                <a href="{{ route('portfolios.template', $portfolio) }}" class="btn btn-ghost btn-sm max-sm:flex-col max-sm:gap-1 max-sm:px-1 max-sm:text-xs"><x-lucide-palette /> Template</a>
                                <button type="button" class="btn btn-ghost btn-sm max-sm:flex-col max-sm:gap-1 max-sm:px-1 max-sm:text-xs sm:ml-auto text-danger-600 hover:bg-danger-50 hover:text-danger-700"
                                        onclick="document.getElementById('delete-{{ $portfolio->id }}').showModal()">
                                    <x-lucide-trash-2 /> Delete
                                </button>
                            </div>
                        </div>
                        <x-delete-dialog :portfolio="$portfolio" />
                    </article>
                @endforeach
            </section>
        @endif
    </div>
</x-layouts.app>
