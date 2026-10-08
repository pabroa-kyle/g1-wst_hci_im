@props(['portfolio'])
@php
    $shared = $portfolio->isShared();
    $url = $portfolio->publicUrl();
    $suggested = $portfolio->slug ?: \App\Models\Portfolio::uniqueSlug($portfolio->full_name, $portfolio->id);
    $slugError = $errors->first('slug');
@endphp
<section id="share" aria-labelledby="share-heading"
         class="border-b border-n-200 bg-white px-4 py-3 sm:px-6 lg:px-10"
         x-data="{ editing: {{ $slugError ? 'true' : 'false' }}, copied: false }">
    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex min-w-0 flex-wrap items-center gap-x-3 gap-y-2">
            <h2 id="share-heading" class="flex items-center gap-2 text-sm font-semibold text-n-900">
                <x-lucide-link class="size-4 text-ink-500" /> Public link
            </h2>

            @if (! $portfolio->generated_at)
                <p class="text-sm text-n-600">Generate your portfolio first, then you can share it.</p>
            @elseif ($shared)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-go-50 px-2.5 py-1 text-xs font-semibold text-go-700">
                    <span class="size-1.5 rounded-full bg-go-400"></span> On · anyone with the link can view
                </span>
                <div class="flex min-w-0 items-center gap-1 rounded-(--radius-ui) border border-n-300 bg-n-50 py-1 pr-1 pl-3">
                    <a href="{{ $url }}" target="_blank" rel="noopener" class="min-w-0 truncate text-sm font-medium text-ink-600 hover:underline">{{ preg_replace('#^https?://#', '', $url) }}</a>
                    <button type="button" class="btn btn-sm btn-ghost shrink-0 px-2"
                            @click="navigator.clipboard.writeText(@js($url)); copied = true; setTimeout(() => copied = false, 2000)">
                        <x-lucide-copy x-show="!copied" /><x-lucide-check x-show="copied" x-cloak class="text-go-700" />
                        <span x-text="copied ? 'Copied' : 'Copy link'">Copy link</span>
                    </button>
                </div>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-full bg-n-100 px-2.5 py-1 text-xs font-semibold text-n-600">
                    <span class="size-1.5 rounded-full bg-n-400"></span> Off · only you can see this portfolio
                </span>
            @endif
        </div>

        @if ($portfolio->generated_at)
            <div class="flex shrink-0 flex-wrap items-center gap-2">
                <button type="button" class="btn btn-ghost btn-sm" @click="editing = !editing" :aria-expanded="editing" aria-controls="share-slug">
                    <x-lucide-pencil /> Change link
                </button>
                <form method="POST" action="{{ route('portfolios.share', $portfolio) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="is_public" value="{{ $shared ? 0 : 1 }}">
                    <button type="submit" class="btn btn-sm {{ $shared ? 'btn-secondary' : 'btn-primary' }}">
                        @if ($shared) <x-lucide-eye-off /> Stop sharing @else <x-lucide-link /> Turn on sharing @endif
                    </button>
                </form>
            </div>
        @endif
    </div>

    @error('share')
        <p class="field-error"><x-lucide-circle-alert class="size-3.5 shrink-0" /> {{ $message }}</p>
    @enderror

    @if ($portfolio->generated_at)
        <form id="share-slug" method="POST" action="{{ route('portfolios.share', $portfolio) }}" x-show="editing" x-cloak
              class="mt-3 flex flex-col gap-2 border-t border-n-200 pt-3 sm:flex-row sm:items-start">
            @csrf @method('PUT')
            <input type="hidden" name="is_public" value="{{ $shared ? 1 : 0 }}">
            <div class="flex-1" data-field>
                <label for="slug" class="sr-only">Link address</label>
                <div class="flex items-stretch overflow-hidden rounded-(--radius-ui) border border-n-300 bg-n-0 focus-within:border-ink-500 focus-within:ring-3 focus-within:ring-ink-100 {{ $slugError ? 'border-danger-600' : '' }}">
                    <span class="flex items-center bg-n-100 px-3 text-sm text-n-600">{{ preg_replace('#^https?://#', '', url('/p')) }}/</span>
                    <input id="slug" name="slug" type="text" maxlength="80" value="{{ old('slug', $suggested) }}"
                           class="min-w-0 flex-1 px-3 py-2 text-sm text-n-900 outline-none" aria-invalid="{{ $slugError ? 'true' : 'false' }}"
                           @if ($slugError) aria-describedby="slug-error" @else aria-describedby="slug-help" @endif>
                </div>
                @if ($slugError)
                    <p class="field-error" id="slug-error"><x-lucide-circle-alert class="size-3.5 shrink-0" /> {{ $slugError }}</p>
                @else
                    <p class="field-help" id="slug-help">Lowercase letters, numbers, and dashes. Changing it breaks the old link.</p>
                @endif
            </div>
            <button type="submit" class="btn btn-secondary btn-sm sm:mt-1">Save link</button>
        </form>
    @endif
</section>
