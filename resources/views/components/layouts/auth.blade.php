@props(['title', 'heading', 'lead'])
<x-layouts.base :title="$title" body-class="bg-n-0">
    <div class="grid min-h-dvh lg:grid-cols-[minmax(0,5fr)_minmax(0,6fr)]">
        <aside class="relative hidden overflow-hidden bg-ink-500 lg:flex lg:flex-col lg:p-10">
            <a href="{{ route('home') }}" aria-label="Folio home"><x-logo tone="white" /></a>
            <div class="relative mt-10 min-h-0 flex-1 overflow-hidden">
                <x-sheet-frame :src="route('samples.show', 'modern')" class="absolute top-4 right-[-18%] left-[8%] aspect-[4/5] rotate-[-3deg]" label="Modern template sample" />
                <x-sheet-frame :src="route('samples.show', 'creative')" class="absolute top-[22%] right-[22%] left-[-14%] aspect-[4/5] rotate-[2deg]" label="Creative template sample" />
            </div>
            <p class="relative z-10 mt-0 max-w-[38ch] border-t border-ink-400/60 pt-6 text-[0.9375rem] leading-relaxed text-ink-100">
                One set of information, three templates. Switch between them anytime without retyping a thing.
            </p>
        </aside>

        <main id="main" class="flex flex-col px-4 py-6 sm:px-6 lg:px-16 lg:py-10">
            <div class="flex items-center justify-between lg:justify-end">
                <a href="{{ route('home') }}" class="lg:hidden" aria-label="Folio home"><x-logo /></a>
                <a href="{{ route('home') }}" class="btn btn-ghost btn-sm"><x-lucide-arrow-left /> Home</a>
            </div>
            <div class="mx-auto flex w-full max-w-md flex-1 flex-col justify-center py-10">
                <h1 class="font-wide text-[clamp(2rem,3.4vw,2.75rem)] leading-[1.02] font-extrabold tracking-[-0.03em] text-n-950">{{ $heading }}</h1>
                <p class="mt-3 leading-relaxed text-n-600">{{ $lead }}</p>
                <div class="mt-8">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</x-layouts.base>
