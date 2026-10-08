@props(['label', 'field', 'type' => 'text', 'optional' => false, 'placeholder' => '', 'max' => 255, 'multiline' => false, 'help' => null])
{{-- A field inside a repeater row (rendered within an Alpine x-for). --}}
<div data-field {{ $attributes }}>
    <label class="field-label" :for="`{{ $field }}-${row._k}`">
        <span>{{ $label }}</span>
        @if ($optional) <span class="optional">Optional</span> @endif
    </label>
    @if ($multiline)
        <textarea class="field-input min-h-24" rows="3" maxlength="{{ $max }}" placeholder="{{ $placeholder }}"
                  :id="`{{ $field }}-${row._k}`" :name="`items[${i}][{{ $field }}]`" x-model="row.{{ $field }}"
                  :aria-invalid="error(i, '{{ $field }}') ? 'true' : 'false'"></textarea>
    @else
        <input type="{{ $type }}" class="field-input" maxlength="{{ $max }}" placeholder="{{ $placeholder }}"
               :id="`{{ $field }}-${row._k}`" :name="`items[${i}][{{ $field }}]`" x-model="row.{{ $field }}"
               :aria-invalid="error(i, '{{ $field }}') ? 'true' : 'false'">
    @endif
    <p class="field-error" x-show="error(i, '{{ $field }}')" x-cloak>
        <x-lucide-circle-alert class="size-3.5 shrink-0" /> <span x-text="error(i, '{{ $field }}')"></span>
    </p>
    @if ($help)
        <p class="field-help" x-show="!error(i, '{{ $field }}')">{{ $help }}</p>
    @endif
</div>
