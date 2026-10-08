@if (session('status'))
    <div x-data="{ open: true }" x-show="open" x-transition.opacity.duration.200ms
         class="mx-4 mt-4 flex items-start gap-3 rounded-(--radius-ui) border border-go-400/40 bg-go-50 px-4 py-3 text-sm text-go-700 sm:mx-6 lg:mx-10 lg:mt-6"
         role="status">
        <x-lucide-circle-check class="mt-px size-[18px] shrink-0" />
        <p class="flex-1 font-medium">{{ session('status') }}</p>
        <button type="button" class="-my-1 -mr-1 rounded-(--radius-ui) p-1 hover:bg-go-400/15" @click="open = false" aria-label="Dismiss">
            <x-lucide-x class="size-4" />
        </button>
    </div>
@endif
