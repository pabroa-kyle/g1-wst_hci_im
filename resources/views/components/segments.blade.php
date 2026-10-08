@props(['portfolio', 'labels' => false])
{{-- Six completeness segments. Each fill length is the section's exact completion. --}}
@php($values = $portfolio->completeness())
<div {{ $attributes->merge(['class' => 'grid grid-cols-6 gap-1']) }} role="img"
     aria-label="{{ $portfolio->completionPercent() }}% complete: {{ collect($values)->map(fn ($v, $k) => \App\Models\Portfolio::STEPS[$k]['label'].' '.round($v * 100).'%')->implode(', ') }}">
    @foreach ($values as $step => $value)
        <div>
            <div class="h-1.5 overflow-hidden rounded-full bg-n-200">
                <div @class(['h-full rounded-full', 'bg-go-400' => $value >= 1, 'bg-ink-500' => $value < 1]) style="width: {{ round($value * 100, 1) }}%"></div>
            </div>
            @if ($labels)
                <p class="mt-1.5 truncate text-[0.6875rem] font-medium text-n-500">{{ \App\Models\Portfolio::STEPS[$step]['label'] }}</p>
            @endif
        </div>
    @endforeach
</div>
