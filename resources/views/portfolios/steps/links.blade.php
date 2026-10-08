@php
    $rows = old('items', $portfolio->links->map->only(['label', 'url'])->all());
    $blank = ['label' => '', 'url' => ''];
    $suggestions = ['GitHub', 'LinkedIn', 'Facebook', 'Instagram', 'Behance', 'Dribbble', 'YouTube', 'Website'];
@endphp
<x-step-form :portfolio="$portfolio" :step="$step"
    intro="Link your social media profiles, personal website, or online work. This is the last section. After saving, you’ll choose a template.">
    <datalist id="link-labels">
        @foreach ($suggestions as $s) <option value="{{ $s }}"></option> @endforeach
    </datalist>
    <x-repeater :rows="$rows" :blank="$blank" noun="link" add-label="Add link" empty="No links added yet" compact>
        <div class="grid items-start gap-x-3 gap-y-3 sm:grid-cols-[12rem_minmax(0,1fr)_auto]">
            <div data-field>
                <label class="sr-only" :for="`label-${row._k}`">Platform</label>
                <input type="text" list="link-labels" class="field-input" maxlength="60" placeholder="GitHub"
                       :id="`label-${row._k}`" :name="`items[${i}][label]`" x-model="row.label"
                       :aria-invalid="error(i, 'label') ? 'true' : 'false'">
                <p class="field-error" x-show="error(i, 'label')" x-cloak><x-lucide-circle-alert class="size-3.5 shrink-0" /> <span x-text="error(i, 'label')"></span></p>
            </div>
            <div data-field>
                <label class="sr-only" :for="`url-${row._k}`">Web address</label>
                <input type="url" class="field-input" maxlength="255" placeholder="https://github.com/you"
                       :id="`url-${row._k}`" :name="`items[${i}][url]`" x-model="row.url"
                       :aria-invalid="error(i, 'url') ? 'true' : 'false'">
                <p class="field-error" x-show="error(i, 'url')" x-cloak><x-lucide-circle-alert class="size-3.5 shrink-0" /> <span x-text="error(i, 'url')"></span></p>
            </div>
            <div class="flex items-center justify-end gap-0.5 pt-1">
                <button type="button" class="btn btn-ghost btn-sm px-2" @click="move(i, -1)" :disabled="i === 0" :aria-label="`Move link ${i + 1} up`"><x-lucide-chevron-up /></button>
                <button type="button" class="btn btn-ghost btn-sm px-2" @click="move(i, 1)" :disabled="i === rows.length - 1" :aria-label="`Move link ${i + 1} down`"><x-lucide-chevron-down /></button>
                <button type="button" class="btn btn-ghost btn-sm px-2 text-danger-600 hover:bg-danger-50 hover:text-danger-700" @click="remove(i)" :aria-label="`Remove link ${i + 1}`"><x-lucide-x /></button>
            </div>
        </div>
    </x-repeater>
</x-step-form>
