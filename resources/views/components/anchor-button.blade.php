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
        'bg-red-600 hover:bg-red-500 active:bg-red-600 focus:ring-red-200 focus:border-red-700' => $btnType == AnchorBtnType::DANGER,
        'bg-white shadow-sm text-gray-700 border-gray-300 active:bg-gray-50 hover:text-gray-500 focus:ring-blue-200 active:text-gray-800 focus:border-blue-300' => $btnType == AnchorBtnType::SECONDARY,
        'hover:bg-gray-700 active:bg-gray-900 focus:ring-gray-300 focus:border-gray-900 bg-gray-800' => $btnType == AnchorBtnType::PRIMARY,
        'hover:bg-blue-700 active:bg-blue-900 focus:ring-blue-300 focus:border-blue-900 bg-blue-800' => $btnType == AnchorBtnType::INFO,
        'hover:bg-yellow-500 active:bg-yellow-600 focus:ring-yellow-300 focus:border-yellow-700 bg-yellow-400' => $btnType == AnchorBtnType::CAUTION,
    ];
@endphp
<a {{ $attributes }}
   @class(array_merge($conditionalAnchorBtnClasses, $universalAnchorBtnCssClasses))
   role="button"
   type="{{ $btnType->value }}">
    {{ $slot }}
</a>
