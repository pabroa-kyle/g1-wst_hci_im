@props(['src', 'width' => 1280, 'label' => 'Portfolio preview'])
{{-- A live page rendered at full width and scaled down to a sheet. --}}
<div x-data="sheet({{ (int) $width }})" {{ $attributes->merge(['class' => 'sheet']) }}>
    <iframe src="{{ $src }}" title="{{ $label }}" loading="lazy" tabindex="-1" aria-hidden="true"
            class="pointer-events-none absolute top-0 left-0 origin-top-left border-0 bg-white"
            style="width: {{ (int) $width }}px; height: 900px; transform: scale(0.25)"></iframe>
</div>
