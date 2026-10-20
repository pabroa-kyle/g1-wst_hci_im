@props(['label', 'name', 'for' => null, 'optional' => false, 'help' => null, 'lettersOnly' => false])
@php($error = $errors->first($name))
<div data-field {{ $attributes }}>
    <label for="{{ $for ?? $name }}" class="field-label">
        <span>{{ $label }}</span>
        @if ($optional) <span class="optional">Optional</span> @endif
    </label>
    {{ $slot }}
    @if ($lettersOnly)
        <p class="field-help font-medium text-ink-600" data-letters-hint hidden role="status">Letters and spaces only. Numbers and special characters aren’t allowed.</p>
    @endif
    @if ($error)
        <p class="field-error" id="{{ $for ?? $name }}-error"><x-lucide-circle-alert class="size-3.5 shrink-0" /> {{ $error }}</p>
    @elseif ($help)
        <p class="field-help" id="{{ $for ?? $name }}-help">{{ $help }}</p>
    @endif
</div>
