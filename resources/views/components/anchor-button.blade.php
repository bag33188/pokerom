@php
    $conditionalAnchorBtnClasses = [
        'bg-red-600' => $btnType == \App\Enums\AnchorTypesEnum::DANGER,
        'text-white' => $btnType == \App\Enums\AnchorTypesEnum::DANGER || $btnType == \App\Enums\AnchorTypesEnum::PRIMARY,
        'hover:bg-red-500' => $btnType == \App\Enums\AnchorTypesEnum::DANGER,
        'active:bg-red-600' => $btnType == \App\Enums\AnchorTypesEnum::DANGER,
        'border-transparent' => $btnType == \App\Enums\AnchorTypesEnum::DANGER || $btnType == \App\Enums\AnchorTypesEnum::PRIMARY,
        'focus:ring-red-200' => $btnType == \App\Enums\AnchorTypesEnum::DANGER,
        'focus:border-red-700' => $btnType == \App\Enums\AnchorTypesEnum::DANGER,
        'bg-white' => $btnType == \App\Enums\AnchorTypesEnum::SECONDARY,
        'shadow-sm' => $btnType == \App\Enums\AnchorTypesEnum::SECONDARY,
        'text-gray-700' => $btnType == \App\Enums\AnchorTypesEnum::SECONDARY,
        'border-gray-300' => $btnType == \App\Enums\AnchorTypesEnum::SECONDARY,
        'active:bg-gray-50' => $btnType == \App\Enums\AnchorTypesEnum::SECONDARY,
        'hover:text-gray-500' => $btnType == \App\Enums\AnchorTypesEnum::SECONDARY,
        'focus:ring-blue-200' => $btnType == \App\Enums\AnchorTypesEnum::SECONDARY,
        'active:text-gray-800' => $btnType == \App\Enums\AnchorTypesEnum::SECONDARY,
        'focus:border-blue-300' => $btnType == \App\Enums\AnchorTypesEnum::SECONDARY,
        'hover:bg-gray-700' => $btnType == \App\Enums\AnchorTypesEnum::PRIMARY,
        'active:bg-gray-900' => $btnType == \App\Enums\AnchorTypesEnum::PRIMARY,
        'focus:ring-gray-300' => $btnType == \App\Enums\AnchorTypesEnum::PRIMARY,
        'focus:border-gray-900' => $btnType == \App\Enums\AnchorTypesEnum::PRIMARY,
        'bg-gray-800' => $btnType == \App\Enums\AnchorTypesEnum::PRIMARY,
    ];

    $universalAnchorCssClasses = [
        'focus:outline-none',
        'disabled:opacity-25',
        'px-4',
        'py-2',
        'border',
        'text-xs',
        'uppercase',
        'rounded-md',
        'focus:ring',
        'transition',
        'inline-flex',
        'items-center',
        'font-semibold',
        'tracking-widest',
        'focus:outline-none',
        'disabled:opacity-25',
    ];
@endphp
<a {{ $attributes }} @class(array_merge($conditionalAnchorBtnClasses, $universalAnchorCssClasses)) role="button"
   type="{{ $btnType->value }}">{{ $slot }}</a>
