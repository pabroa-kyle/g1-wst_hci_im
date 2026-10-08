@props(['rows', 'blank', 'noun', 'addLabel', 'empty', 'titleField' => null, 'compact' => false])
@php($rowErrors = collect($errors->getMessages())->filter(fn ($m, $k) => str_starts_with($k, 'items.'))->all())
<div x-data="repeater(@js(array_values($rows)), @js($blank), @js($rowErrors))">
    <div x-show="rows.length === 0" class="rounded-(--radius-ui) border border-dashed border-n-300 bg-n-0 px-6 py-10 text-center">
        <p class="font-semibold text-n-800">{{ $empty }}</p>
        <p class="mt-1 text-sm text-n-600">Add one below. Leave this section empty if it doesn’t apply to you.</p>
    </div>

    <ol @class(['space-y-4' => ! $compact, 'space-y-2' => $compact])>
        <template x-for="(row, i) in rows" :key="row._k">
            <li data-row @class(['panel', 'p-5 sm:p-6' => ! $compact, 'px-4 py-3' => $compact])>
                @unless ($compact)
                    <div class="mb-5 flex items-center justify-between gap-3 border-b border-n-200 pb-3">
                        <p class="min-w-0 truncate text-sm font-semibold text-n-800">
                            <span class="text-n-400 tabular-nums" x-text="`${i + 1}.`"></span>
                            <span x-text="{{ $titleField ? "row.$titleField || 'New ".e($noun)."'" : "'".e(ucfirst($noun))."'" }}"></span>
                        </p>
                        <div class="flex shrink-0 items-center gap-0.5">
                            <button type="button" class="btn btn-ghost btn-sm px-2" @click="move(i, -1)" :disabled="i === 0" :aria-label="`Move {{ $noun }} ${i + 1} up`"><x-lucide-chevron-up /></button>
                            <button type="button" class="btn btn-ghost btn-sm px-2" @click="move(i, 1)" :disabled="i === rows.length - 1" :aria-label="`Move {{ $noun }} ${i + 1} down`"><x-lucide-chevron-down /></button>
                            <button type="button" class="btn btn-ghost btn-sm text-danger-600 hover:bg-danger-50 hover:text-danger-700" @click="remove(i)" :aria-label="`Remove {{ $noun }} ${i + 1}`"><x-lucide-trash-2 /> <span class="hidden sm:inline">Remove</span></button>
                        </div>
                    </div>
                @endunless
                {{ $slot }}
            </li>
        </template>
    </ol>

    <button type="button" @click="add()" class="btn btn-secondary mt-4 w-full border-dashed py-3 sm:w-auto">
        <x-lucide-plus /> {{ $addLabel }}
    </button>
</div>
