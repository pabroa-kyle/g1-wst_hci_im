@props(['portfolio'])
{{-- Deleting is permanent, so it gets a confirmation with protected focus. --}}
<dialog id="delete-{{ $portfolio->id }}"
        class="m-auto w-[min(28rem,calc(100vw-2rem))] rounded-(--radius-ui) border border-n-200 bg-white p-0 text-n-900 shadow-[0_24px_48px_-16px_rgb(10_17_69/0.35)] backdrop:bg-n-950/50">
    <form method="POST" action="{{ route('portfolios.destroy', $portfolio) }}" class="p-6">
        @csrf
        @method('DELETE')
        <div class="mb-4 flex size-10 items-center justify-center rounded-full bg-danger-50 text-danger-600">
            <x-lucide-trash-2 class="size-5" />
        </div>
        <h2 class="font-semiwide text-lg font-bold">Delete “{{ $portfolio->title }}”?</h2>
        <p class="mt-2 text-sm leading-relaxed text-n-600">
            This permanently removes the portfolio, all of its sections, and its profile picture from the online database. You can’t undo this.
        </p>
        <div class="mt-6 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button type="button" class="btn btn-secondary" onclick="this.closest('dialog').close()" autofocus>Keep portfolio</button>
            <button type="submit" class="btn btn-danger"><x-lucide-trash-2 /> Delete portfolio</button>
        </div>
    </form>
</dialog>
