@props(['type'])
@php

    $resolveAnchorBtnType(btnType: $type, defaultTypeValue: \App\Enums\AnchorTypesEnum::PRIMARY->value, btnTypeEnumCases: \App\Enums\AnchorTypesEnum::cases());

    $conditionalAnchorBtnClasses = [
        'bg-red-600' => $type == \App\Enums\AnchorTypesEnum::DANGER->value,
        'text-white' => $type == \App\Enums\AnchorTypesEnum::DANGER->value || $type == \App\Enums\AnchorTypesEnum::PRIMARY->value,
        'hover:bg-red-500' => $type == \App\Enums\AnchorTypesEnum::DANGER->value,
        'active:bg-red-600' => $type == \App\Enums\AnchorTypesEnum::DANGER->value,
        'border-transparent' => $type == \App\Enums\AnchorTypesEnum::DANGER->value || $type == \App\Enums\AnchorTypesEnum::PRIMARY->value,
        'focus:ring-red-200' => $type == \App\Enums\AnchorTypesEnum::DANGER->value,
        'focus:border-red-700' => $type == \App\Enums\AnchorTypesEnum::DANGER->value,
        'bg-white' => $type == \App\Enums\AnchorTypesEnum::SECONDARY->value,
        'shadow-sm' => $type == \App\Enums\AnchorTypesEnum::SECONDARY->value,
        'text-gray-700' => $type == \App\Enums\AnchorTypesEnum::SECONDARY->value,
        'border-gray-300' => $type == \App\Enums\AnchorTypesEnum::SECONDARY->value,
        'active:bg-gray-50' => $type == \App\Enums\AnchorTypesEnum::SECONDARY->value,
        'hover:text-gray-500' => $type == \App\Enums\AnchorTypesEnum::SECONDARY->value,
        'focus:ring-blue-200' => $type == \App\Enums\AnchorTypesEnum::SECONDARY->value,
        'active:text-gray-800' => $type == \App\Enums\AnchorTypesEnum::SECONDARY->value,
        'focus:border-blue-300' => $type == \App\Enums\AnchorTypesEnum::SECONDARY->value,
        'hover:bg-gray-700' => $type == \App\Enums\AnchorTypesEnum::PRIMARY->value,
        'active:bg-gray-900' => $type == \App\Enums\AnchorTypesEnum::PRIMARY->value,
        'focus:ring-gray-300' => $type == \App\Enums\AnchorTypesEnum::PRIMARY->value,
        'focus:border-gray-900' => $type == \App\Enums\AnchorTypesEnum::PRIMARY->value,
        'bg-gray-800' => $type == \App\Enums\AnchorTypesEnum::PRIMARY->value,
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
<a {{ $attributes }} @class(array_merge($conditionalAnchorBtnClasses, $universalAnchorCssClasses))>{{ $slot }}</a>
