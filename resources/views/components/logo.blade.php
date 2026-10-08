@props(['tone' => 'ink'])
{{-- Folio mark: three stacked sheets, one identity applied three ways. --}}
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
    <svg viewBox="0 0 28 28" class="size-7 shrink-0" aria-hidden="true">
        @if ($tone === 'white')
            <rect x="9" y="2" width="15" height="19" rx="1" fill="#fff" opacity=".35"/>
            <rect x="6" y="5" width="15" height="19" rx="1" fill="#fff" opacity=".6"/>
            <rect x="3" y="8" width="15" height="19" rx="1" fill="#fff"/>
            <rect x="6" y="12" width="9" height="2" fill="#1f33c9"/>
            <rect x="6" y="16" width="6" height="1.5" fill="#1f33c9" opacity=".55"/>
        @else
            <rect x="9" y="2" width="15" height="19" rx="1" fill="#1f33c9" opacity=".3"/>
            <rect x="6" y="5" width="15" height="19" rx="1" fill="#1f33c9" opacity=".6"/>
            <rect x="3" y="8" width="15" height="19" rx="1" fill="#1f33c9"/>
            <rect x="6" y="12" width="9" height="2" fill="#fff"/>
            <rect x="6" y="16" width="6" height="1.5" fill="#fff" opacity=".7"/>
        @endif
    </svg>
    <span @class([
        'font-wide text-xl font-extrabold tracking-[-0.02em]',
        'text-white' => $tone === 'white',
        'text-n-950' => $tone !== 'white',
    ])>Folio</span>
</span>
