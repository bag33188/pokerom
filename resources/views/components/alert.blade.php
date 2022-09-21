@props(['heading', 'message'])

@php
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

    $conditionalAlertCssClasses = [
        'bg-green-100' => $alertType === \App\Enums\AlertTypesEnum::SUCCESS,
        'border-green-400' => $alertType === \App\Enums\AlertTypesEnum::SUCCESS,
        'text-green-700' => $alertType === \App\Enums\AlertTypesEnum::SUCCESS,
        'bg-red-100' => $alertType === \App\Enums\AlertTypesEnum::ERROR,
        'border-red-400' => $alertType === \App\Enums\AlertTypesEnum::ERROR,
        'text-red-700' => $alertType === \App\Enums\AlertTypesEnum::ERROR,
        'bg-yellow-100' => $alertType === \App\Enums\AlertTypesEnum::WARNING,
        'border-yellow-400' => $alertType === \App\Enums\AlertTypesEnum::WARNING,
        'text-yellow-700' => $alertType === \App\Enums\AlertTypesEnum::WARNING,
        'bg-blue-100' => $alertType === \App\Enums\AlertTypesEnum::DEFAULT,
        'border-blue-400' => $alertType === \App\Enums\AlertTypesEnum::DEFAULT,
        'text-blue-700' => $alertType === \App\Enums\AlertTypesEnum::DEFAULT,
    ];
@endphp
<div @class(array_merge($universalAlertCssClasses, $conditionalAlertCssClasses))
     role="alert"
     type="{{ $alertType->value }}">
    <p class="sm:inline text-lg">
        <strong class="font-bold">{{ $heading }}</strong>
        <span class="block">{{ $message }}</span>
    </p>
</div>
