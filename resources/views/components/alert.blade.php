@props(['heading', 'message'])

@php
    $resolveAlertType(alertType: $type, defaultTypeValue: \App\Enums\AlertTypesEnum::DEFAULT->value, alertTypeEnumCases: \App\Enums\AlertTypesEnum::cases());

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
        'bg-green-100' => $type === \App\Enums\AlertTypesEnum::SUCCESS->value,
        'border-green-400' => $type === \App\Enums\AlertTypesEnum::SUCCESS->value,
        'text-green-700' => $type === \App\Enums\AlertTypesEnum::SUCCESS->value,
        'bg-red-100' => $type === \App\Enums\AlertTypesEnum::ERROR->value,
        'border-red-400' => $type === \App\Enums\AlertTypesEnum::ERROR->value,
        'text-red-700' => $type === \App\Enums\AlertTypesEnum::ERROR->value,
        'bg-yellow-100' => $type === \App\Enums\AlertTypesEnum::WARNING->value,
        'border-yellow-400' => $type === \App\Enums\AlertTypesEnum::WARNING->value,
        'text-yellow-700' => $type === \App\Enums\AlertTypesEnum::WARNING->value,
        'bg-blue-100' => $type === \App\Enums\AlertTypesEnum::DEFAULT->value,
        'border-blue-400' => $type === \App\Enums\AlertTypesEnum::DEFAULT->value,
        'text-blue-700' => $type === \App\Enums\AlertTypesEnum::DEFAULT->value,
    ];
@endphp
<div @class(array_merge($universalAlertCssClasses, $conditionalAlertCssClasses)) role="alert" type="{{ $type }}">
    <p class="sm:inline text-lg">
        <strong class="font-bold">{{ $heading }}</strong>
        <span class="block">{{ $message }}</span>
    </p>
</div>
