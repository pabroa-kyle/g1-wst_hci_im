@props(['title' => null, 'bodyClass' => ''])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1f33c9">
    <title>{{ $title ? $title.' · Folio' : 'Folio · Portfolio Template Generator' }}</title>
    <meta name="description" content="Folio is a portfolio template generator. Enter your information once, save it online, and generate your portfolio in a Simple, Modern, or Creative template.">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body {{ $attributes->merge(['class' => 'min-h-dvh '.$bodyClass]) }}>
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded-(--radius-ui) focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-ink-600">Skip to content</a>
    {{ $slot }}
</body>
</html>
