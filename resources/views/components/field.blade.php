@props(['label', 'name', 'for' => null, 'optional' => false, 'help' => null])
@php($error = $errors->first($name))
<div data-field {{ $attributes }}>
    <label for="{{ $for ?? $name }}" class="field-label">
        <span>{{ $label }}</span>
        @if ($optional) <span class="optional">Optional</span> @endif
    </label>
    {{ $slot }}
    @if ($error)
        <p class="field-error" id="{{ $for ?? $name }}-error"><x-lucide-circle-alert class="size-3.5 shrink-0" /> {{ $error }}</p>
    @elseif ($help)
        <p class="field-help" id="{{ $for ?? $name }}-help">{{ $help }}</p>
    @endif
</div>
