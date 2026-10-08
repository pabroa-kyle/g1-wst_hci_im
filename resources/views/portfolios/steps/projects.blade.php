@php
    $fields = ['title', 'role', 'year', 'tech', 'url', 'description'];
    $rows = old('items', $portfolio->projects->map->only($fields)->all());
    $blank = array_fill_keys($fields, '');
@endphp
<x-step-form :portfolio="$portfolio" :step="$step"
    intro="Show what you’ve built: school projects, capstones, freelance work, or personal experiments. Put your strongest project first.">
    <x-repeater :rows="$rows" :blank="$blank" noun="project" title-field="title"
                add-label="Add project" empty="No projects added yet">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-rfield label="Project title" field="title" :max="160" placeholder="Barangay Health Records" class="sm:col-span-2" />
            <x-rfield label="Your role" field="role" :max="120" optional placeholder="Lead developer" />
            <x-rfield label="Year" field="year" :max="10" optional placeholder="2026" />
            <x-rfield label="Tools & technologies" field="tech" optional placeholder="Laravel, PostgreSQL, Tailwind" help="Separate with commas." class="sm:col-span-2" />
            <x-rfield label="Link" field="url" type="url" optional placeholder="https://github.com/you/project" help="A live demo or repository." class="sm:col-span-2" />
            <x-rfield label="Description" field="description" multiline :max="1000" optional placeholder="What it does, what you did, and the result." class="sm:col-span-2" />
        </div>
    </x-repeater>
</x-step-form>
