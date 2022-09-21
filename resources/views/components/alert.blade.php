@props(['heading', 'message'])
@php
    use App\Enums\AlertTypeEnum as AlertType;

    $universalAlertCssClasses = [
        'my-6',
        'mx-4',
        'border',
        'px-4',
        'py-3',
        'rounded',
        'relative',
        'text-center',
    ];
    $conditionalAlertCssClasses = [];
//
//    $conditionalAlertCssClasses = [
//        'bg-green-100' => $alertType == AlertType::SUCCESS,
//        'border-green-400' => $alertType == AlertType::SUCCESS,
//        'text-green-700' => $alertType == AlertType::SUCCESS,
//        'bg-red-100' => $alertType == AlertType::ERROR,
//        'border-red-400' => $alertType == AlertType::ERROR,
//        'text-red-700' => $alertType == AlertType::ERROR,
//        'bg-yellow-100' => $alertType == AlertType::WARNING,
//        'border-yellow-400' => $alertType == AlertType::WARNING,
//        'text-yellow-700' => $alertType == AlertType::WARNING,
//        'bg-blue-100' => $alertType == AlertType::DEFAULT,
//        'border-blue-400' => $alertType == AlertType::DEFAULT,
//        'text-blue-700' => $alertType == AlertType::DEFAULT,
//    ];
//    $conditionalAlertCssClasses1 = [
//        'bg-green-100 border-green-400 text-green-700' => $alertType == AlertType::SUCCESS,
//        'bg-red-100 border-red-400 text-red-700' => $alertType == AlertType::ERROR,
//        'bg-yellow-100 border-yellow-400 text-yellow-700' => $alertType == AlertType::WARNING,
//        'bg-blue-100 border-blue-400 text-blue-700' => $alertType == AlertType::DEFAULT,
//    ];
    switch ($alertType) {
        case AlertType::SUCCESS:
            $conditionalAlertCssClasses = [
                'bg-green-100',
                'border-green-400',
                'text-green-700',
            ];
            break;
        case AlertType::ERROR:
            $conditionalAlertCssClasses = [
                'bg-red-100',
                'border-red-400',
                'text-red-700',
            ];
            break;
        case AlertType::WARNING:
            $conditionalAlertCssClasses = [
                'bg-yellow-100',
                'border-yellow-400',
                'text-yellow-700',
            ];
            break;
        case AlertType::INFO:
            $conditionalAlertCssClasses = [
                'bg-blue-100',
                'border-blue-400',
                'text-blue-700',
            ];
            break;
        default:
            $conditionalAlertCssClasses = [
                'bg-gray-100',
                'border-gray-500',
                'text-gray-600',
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
