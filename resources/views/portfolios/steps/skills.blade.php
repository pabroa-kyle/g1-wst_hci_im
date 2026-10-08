@php
    $rows = old('items', $portfolio->skills->map->only(['name', 'level'])->all());
    $blank = ['name' => '', 'level' => 3];
    $levels = [1 => 'Beginner', 2 => 'Basic', 3 => 'Intermediate', 4 => 'Advanced', 5 => 'Expert'];
@endphp
<x-step-form :portfolio="$portfolio" :step="$step"
    intro="Add the skills you want people to notice, then set how confident you are in each one. Three or more skills fills this section.">
    <x-repeater :rows="$rows" :blank="$blank" noun="skill" add-label="Add skill" empty="No skills added yet" compact>
        <div class="grid items-center gap-x-4 gap-y-3 sm:grid-cols-[minmax(0,1fr)_auto_auto]">
            <div data-field>
                <label class="sr-only" :for="`name-${row._k}`">Skill name</label>
                <input type="text" class="field-input" maxlength="80" placeholder="e.g. JavaScript"
                       :id="`name-${row._k}`" :name="`items[${i}][name]`" x-model="row.name"
                       :aria-invalid="error(i, 'name') ? 'true' : 'false'">
                <p class="field-error" x-show="error(i, 'name')" x-cloak><x-lucide-circle-alert class="size-3.5 shrink-0" /> <span x-text="error(i, 'name')"></span></p>
            </div>

            <fieldset class="flex items-center gap-3">
                <legend class="sr-only" x-text="`Level for ${row.name || 'this skill'}`"></legend>
                <div class="flex gap-1">
                    @foreach ($levels as $value => $text)
                        <label class="cursor-pointer" title="{{ $text }}">
                            <input type="radio" class="peer sr-only" value="{{ $value }}" :name="`items[${i}][level]`" x-model.number="row.level">
                            <span class="block h-6 w-5 rounded-[2px] transition-colors duration-150 peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-ink-500"
                                  :class="row.level >= {{ $value }} ? 'bg-ink-500' : 'bg-n-200 hover:bg-n-300'"></span>
                            <span class="sr-only">{{ $text }}</span>
                        </label>
                    @endforeach
                </div>
                <span class="w-24 text-sm font-medium text-n-700" x-text="{{ \Illuminate\Support\Js::from($levels) }}[row.level]"></span>
            </fieldset>

            <div class="flex items-center justify-end gap-0.5">
                <button type="button" class="btn btn-ghost btn-sm px-2" @click="move(i, -1)" :disabled="i === 0" :aria-label="`Move skill ${i + 1} up`"><x-lucide-chevron-up /></button>
                <button type="button" class="btn btn-ghost btn-sm px-2" @click="move(i, 1)" :disabled="i === rows.length - 1" :aria-label="`Move skill ${i + 1} down`"><x-lucide-chevron-down /></button>
                <button type="button" class="btn btn-ghost btn-sm px-2 text-danger-600 hover:bg-danger-50 hover:text-danger-700" @click="remove(i)" :aria-label="`Remove skill ${i + 1}`"><x-lucide-x /></button>
            </div>
        </div>
    </x-repeater>
</x-step-form>
