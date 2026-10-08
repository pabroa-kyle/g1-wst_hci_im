@php
    $fields = ['school', 'degree', 'start_year', 'end_year', 'description'];
    $rows = old('items', $portfolio->educations->map->only($fields)->all());
    $blank = array_fill_keys($fields, '');
@endphp
<x-step-form :portfolio="$portfolio" :step="$step"
    intro="List the schools you attended, most recent first. Use the arrows to reorder entries.">
    <x-repeater :rows="$rows" :blank="$blank" noun="education entry" title-field="school"
                add-label="Add education" empty="No education added yet">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-rfield label="School" field="school" :max="160" placeholder="Northbridge State University" class="sm:col-span-2" />
            <x-rfield label="Degree or program" field="degree" :max="160" optional placeholder="BS Information Technology" class="sm:col-span-2" />
            <x-rfield label="Start year" field="start_year" :max="10" optional placeholder="2023" />
            <x-rfield label="End year" field="end_year" :max="10" optional placeholder="2027 or Present" />
            <x-rfield label="Details" field="description" multiline :max="1000" optional placeholder="Honors, awards, thesis, or relevant coursework" class="sm:col-span-2" />
        </div>
    </x-repeater>
</x-step-form>
