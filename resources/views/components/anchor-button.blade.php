@php
    use App\Enums\AnchorButtonTypeEnum as AnchorBtnType;

    $universalAnchorBtnCssClasses = [
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
        'text-white' => match ($btnType) {
            AnchorBtnType::DANGER, AnchorBtnType::PRIMARY, AnchorBtnType::INFO => true,
            default => false
        },
        'border-transparent' => match ($btnType) {
            AnchorBtnType::DANGER, AnchorBtnType::PRIMARY, AnchorBtnType::CAUTION => true,
            default => false
        },
        'bg-red-600' => $btnType == AnchorBtnType::DANGER,
        'hover:bg-red-500' => $btnType == AnchorBtnType::DANGER,
        'active:bg-red-600' => $btnType == AnchorBtnType::DANGER,
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
        'hover:bg-blue-700' => $btnType == AnchorBtnType::INFO,
        'active:bg-blue-900' => $btnType == AnchorBtnType::INFO,
        'focus:ring-blue-300' => $btnType == AnchorBtnType::INFO,
        'focus:border-blue-900' => $btnType == AnchorBtnType::INFO,
        'bg-blue-800' => $btnType == AnchorBtnType::INFO,
        'hover:bg-yellow-500' => $btnType == AnchorBtnType::CAUTION,
        'active:bg-yellow-600' => $btnType == AnchorBtnType::CAUTION,
        'focus:ring-yellow-300' => $btnType == AnchorBtnType::CAUTION,
        'focus:border-yellow-700' => $btnType == AnchorBtnType::CAUTION,
        'bg-yellow-400' => $btnType == AnchorBtnType::CAUTION,
    ];
@endphp
<a {{ $attributes }}
   @class(array_merge($conditionalAnchorBtnClasses, $universalAnchorBtnCssClasses))
   role="button"
   type="{{ $btnType->value }}">
    {{ $slot }}
</a>
