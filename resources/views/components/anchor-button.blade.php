@php
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

    $conditionalAnchorBtnClasses = [
        'bg-red-600' => $btnType == \App\Enums\AnchorTypeEnum::DANGER,
        'text-white' => $btnType == \App\Enums\AnchorTypeEnum::DANGER || $btnType == \App\Enums\AnchorTypeEnum::PRIMARY,
        'hover:bg-red-500' => $btnType == \App\Enums\AnchorTypeEnum::DANGER,
        'active:bg-red-600' => $btnType == \App\Enums\AnchorTypeEnum::DANGER,
        'border-transparent' => $btnType == \App\Enums\AnchorTypeEnum::DANGER || $btnType == \App\Enums\AnchorTypeEnum::PRIMARY,
        'focus:ring-red-200' => $btnType == \App\Enums\AnchorTypeEnum::DANGER,
        'focus:border-red-700' => $btnType == \App\Enums\AnchorTypeEnum::DANGER,
        'bg-white' => $btnType == \App\Enums\AnchorTypeEnum::SECONDARY,
        'shadow-sm' => $btnType == \App\Enums\AnchorTypeEnum::SECONDARY,
        'text-gray-700' => $btnType == \App\Enums\AnchorTypeEnum::SECONDARY,
        'border-gray-300' => $btnType == \App\Enums\AnchorTypeEnum::SECONDARY,
        'active:bg-gray-50' => $btnType == \App\Enums\AnchorTypeEnum::SECONDARY,
        'hover:text-gray-500' => $btnType == \App\Enums\AnchorTypeEnum::SECONDARY,
        'focus:ring-blue-200' => $btnType == \App\Enums\AnchorTypeEnum::SECONDARY,
        'active:text-gray-800' => $btnType == \App\Enums\AnchorTypeEnum::SECONDARY,
        'focus:border-blue-300' => $btnType == \App\Enums\AnchorTypeEnum::SECONDARY,
        'hover:bg-gray-700' => $btnType == \App\Enums\AnchorTypeEnum::PRIMARY,
        'active:bg-gray-900' => $btnType == \App\Enums\AnchorTypeEnum::PRIMARY,
        'focus:ring-gray-300' => $btnType == \App\Enums\AnchorTypeEnum::PRIMARY,
        'focus:border-gray-900' => $btnType == \App\Enums\AnchorTypeEnum::PRIMARY,
        'bg-gray-800' => $btnType == \App\Enums\AnchorTypeEnum::PRIMARY,
    ];
@endphp
<a {{ $attributes }}
   @class(array_merge($conditionalAnchorBtnClasses, $universalAnchorCssClasses))
   role="button"
   type="{{ $btnType->value }}">
    {{ $slot }}
</a>
