@php
    $fields = ['company', 'role', 'location', 'start_date', 'end_date', 'is_current', 'description'];
    $rows = old('items', $portfolio->experiences->map->only($fields)->all());
    $rows = collect($rows)->map(fn ($row) => ['is_current' => (bool) ($row['is_current'] ?? false)] + $row)->all();
    $blank = array_fill_keys($fields, '');
    $blank['is_current'] = false;
@endphp
<x-step-form :portfolio="$portfolio" :step="$step"
    intro="Jobs, internships, part-time work, and volunteer roles all count. Most recent first.">
    <x-repeater :rows="$rows" :blank="$blank" noun="position" title-field="role"
                add-label="Add work experience" empty="No work experience added yet">
        <div class="grid gap-5 sm:grid-cols-2">
            <x-rfield label="Job title" field="role" :max="160" placeholder="Front-end Intern" />
            <x-rfield label="Company or organisation" field="company" :max="160" placeholder="Brightline Digital" />
            <x-rfield label="Location" field="location" :max="120" optional placeholder="Makati City" class="sm:col-span-2" />
            <x-rfield label="Start" field="start_date" :max="20" optional placeholder="Jun 2026" />
            <div>
                <div x-show="!row.is_current">
                    <x-rfield label="End" field="end_date" :max="20" optional placeholder="Aug 2026" />
                </div>
                <div x-show="row.is_current" x-cloak>
                    <p class="field-label"><span>End</span></p>
                    <p class="flex h-[2.875rem] items-center rounded-(--radius-ui) bg-n-100 px-3 text-[0.9375rem] text-n-600">Present</p>
                </div>
                <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-sm text-n-700">
                    <input type="checkbox" value="1" class="size-4 rounded-[3px] border-n-300 accent-ink-500" :name="`items[${i}][is_current]`" x-model="row.is_current">
                    I currently work here
                </label>
            </div>
            <x-rfield label="What you did" field="description" multiline :max="1000" optional placeholder="Responsibilities and achievements. Numbers help." class="sm:col-span-2" />
        </div>
    </x-repeater>
</x-step-form>
