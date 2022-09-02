@php
    $tileClasses = [
        'border', 'border-gray-200', 'bg-white', 'shadow-md', 'rounded', 'w-full',
        'h-full', 'inline-grid', 'grid-cols-1', 'grid-rows-[auto_auto]', 'gap-y-2', 'p-2'
    ];
@endphp
<div {{ $attributes->merge(['class'=> joinCssClasses($tileClasses)]) }}>
    <div class="p-2 border rounded-md shadow-inner border-gray-300 flex flex-col">
        {{$slot}}
    </div>
</div>
