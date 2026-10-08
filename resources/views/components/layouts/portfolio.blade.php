@props(['p', 'owner' => false, 'bodyClass' => ''])
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $p->full_name }}{{ $p->headline ? ' · '.$p->headline : '' }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit($p->about ?: $p->full_name.' portfolio', 155) }}">
    @unless (request()->routeIs('portfolios.public'))
        <meta name="robots" content="noindex">
    @endunless
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @vite('resources/css/portfolio.css')
</head>
<body class="{{ $bodyClass }}">
    {{ $slot }}

    @if ($owner)
        {{-- Owner controls, only on the full-page view --}}
        <div class="fixed inset-x-0 bottom-4 z-50 flex justify-center px-4 font-[system-ui,sans-serif] print:hidden">
            <div class="flex items-center gap-1 rounded-full bg-[#0d0f1a]/92 p-1.5 pl-4 text-sm text-white shadow-[0_12px_32px_-12px_rgb(0_0_0/0.6)] backdrop-blur">
                <span class="mr-2 hidden text-[#c6ccf6] sm:inline">Your portfolio · {{ $p->templateName() }}</span>
                <a href="{{ route('portfolios.preview', $p) }}" class="rounded-full px-3 py-1.5 font-semibold hover:bg-white/10">Back to Folio</a>
                <a href="{{ route('portfolios.edit', $p) }}" class="rounded-full bg-[#1f33c9] px-3 py-1.5 font-semibold hover:bg-[#1828a6]">Edit</a>
            </div>
        </div>
    @endif
</body>
</html>
