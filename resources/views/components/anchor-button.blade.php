@php
    use App\Enums\AnchorButtonTypeEnum as AnchorBtnType;

    $universalAnchorBtnCssClasses = [
        "px-4", "py-2", "border", "text-xs", "uppercase", "shadow-sm",
        "rounded-md", "focus:ring", "transition", "inline-flex", "items-center",
        "font-semibold", "tracking-widest", "focus:outline-none", "disabled:opacity-25"
    ];

    $conditionalAnchorBtnClasses = [
        'text-white' => match ($btnType) {
            AnchorBtnType::SECONDARY, AnchorBtnType::CAUTION => false,
            default => true
        },
        'border-transparent' => match ($btnType) {
            AnchorBtnType::PRIMARY, AnchorBtnType::CAUTION, AnchorBtnType::DANGER, AnchorBtnType::OK => true,
            default => false
        },
        'bg-gray-800 hover:bg-gray-700 active:bg-gray-900 focus:ring-gray-300 focus:border-gray-900' => $btnType == AnchorBtnType::PRIMARY,
        'bg-white text-gray-700 border-gray-300 active:bg-gray-50 hover:text-gray-500 focus:ring-blue-200 active:text-gray-800 focus:border-blue-300' => $btnType == AnchorBtnType::SECONDARY,
        'bg-blue-800 hover:bg-blue-700 active:bg-blue-900 focus:ring-blue-300 focus:border-blue-900' => $btnType == AnchorBtnType::INFO,
        'bg-green-600 hover:bg-green-500 active:bg-green-700 focus:ring-green-400' => $btnType == AnchorBtnType::OK,
        'bg-yellow-400 hover:bg-yellow-500 active:bg-yellow-600 focus:ring-yellow-300 focus:border-yellow-700' => $btnType == AnchorBtnType::CAUTION,
        'bg-red-600 hover:bg-red-500 active:bg-red-600 focus:ring-red-200 focus:border-red-700' => $btnType == AnchorBtnType::DANGER,
    ];
@endphp
<a {{ $attributes }}
   @class(array_merge($conditionalAnchorBtnClasses, $universalAnchorBtnCssClasses))
   role="button"
   type="{{ $btnType->value }}">
    {{ $slot }}
</a>
