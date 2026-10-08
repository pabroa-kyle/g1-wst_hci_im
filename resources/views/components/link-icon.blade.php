@props(['label' => ''])
@php
    $key = \Illuminate\Support\Str::lower($label);
    $icon = collect(['github', 'gitlab', 'linkedin', 'facebook', 'instagram', 'youtube', 'dribbble', 'figma', 'codepen', 'twitter'])
        ->first(fn ($brand) => str_contains($key, $brand));
    if (! $icon && (str_contains($key, 'x.com') || $key === 'x')) $icon = 'twitter';
    if (! $icon && str_contains($key, 'mail')) $icon = 'mail';
    $icon ??= 'globe';
@endphp
<x-dynamic-component :component="'lucide-'.$icon" {{ $attributes }} aria-hidden="true" />
