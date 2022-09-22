@props(['heading', 'message'])
@php
    use App\Enums\AlertTypeEnum as AlertType;

    $universalAlertCssClasses = [
        'my-6',
        'mx-4',
        'px-4',
        'py-3',
        'border',
        'rounded',
        'relative',
        'text-center'
    ];

    $conditionalAlertCssClasses = array();

    switch ($alertType) {
        case AlertType::SUCCESS:
            $conditionalAlertCssClasses = [
                'bg-green-100',
                'text-green-700',
                'border-green-400',
            ];
            break;
        case AlertType::ERROR:
            $conditionalAlertCssClasses = [
                'bg-red-100',
                'text-red-700',
                'border-red-400',
            ];
            break;
        case AlertType::WARNING:
            $conditionalAlertCssClasses = [
                'bg-yellow-100',
                'text-yellow-700',
                'border-yellow-400',
            ];
            break;
        case AlertType::MESSAGE:
            $conditionalAlertCssClasses = [
                'bg-blue-100',
                'text-blue-700',
                'border-blue-400',
            ];
            break;
        default:
            $conditionalAlertCssClasses = [
                'bg-gray-100',
                'text-gray-600',
                'border-gray-500',
            ];
    }
@endphp
<div @class(array_merge($universalAlertCssClasses, $conditionalAlertCssClasses))
     role="alert"
     type="{{ $alertType->value }}">
    <p class="sm:inline text-lg">
        <strong class="font-bold">{{ $heading }}</strong>
        <span class="block">{{ $message }}</span>
    </p>
</div>
