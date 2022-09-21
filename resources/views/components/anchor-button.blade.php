@php
    use App\Enums\AnchorTypeEnum as AnchorBtnType;

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
        'bg-red-600' => $btnType == AnchorBtnType::DANGER,
        'text-white' => $btnType == AnchorBtnType::DANGER || $btnType == AnchorBtnType::PRIMARY,
        'hover:bg-red-500' => $btnType == AnchorBtnType::DANGER,
        'active:bg-red-600' => $btnType == AnchorBtnType::DANGER,
        'border-transparent' => $btnType == AnchorBtnType::DANGER || $btnType == AnchorBtnType::PRIMARY,
        'focus:ring-red-200' => $btnType == AnchorBtnType::DANGER,
        'focus:border-red-700' => $btnType == AnchorBtnType::DANGER,
        'bg-white' => $btnType == AnchorBtnType::SECONDARY,
        'shadow-sm' => $btnType == AnchorBtnType::SECONDARY,
        'text-gray-700' => $btnType == AnchorBtnType::SECONDARY,
        'border-gray-300' => $btnType == AnchorBtnType::SECONDARY,
        'active:bg-gray-50' => $btnType == AnchorBtnType::SECONDARY,
        'hover:text-gray-500' => $btnType == AnchorBtnType::SECONDARY,
        'focus:ring-blue-200' => $btnType == AnchorBtnType::SECONDARY,
        'active:text-gray-800' => $btnType == AnchorBtnType::SECONDARY,
        'focus:border-blue-300' => $btnType == AnchorBtnType::SECONDARY,
        'hover:bg-gray-700' => $btnType == AnchorBtnType::PRIMARY,
        'active:bg-gray-900' => $btnType == AnchorBtnType::PRIMARY,
        'focus:ring-gray-300' => $btnType == AnchorBtnType::PRIMARY,
        'focus:border-gray-900' => $btnType == AnchorBtnType::PRIMARY,
        'bg-gray-800' => $btnType == AnchorBtnType::PRIMARY,
    ];
@endphp
<a {{ $attributes }}
   @class(array_merge($conditionalAnchorBtnClasses, $universalAnchorCssClasses))
   role="button"
   type="{{ $btnType->value }}">
    {{ $slot }}
</a>
