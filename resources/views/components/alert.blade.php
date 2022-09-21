@props(['heading', 'message'])
@once
    @php
        enum AlertTypes: string {
            case SUCCESS = 'success';
            case ERROR = 'error';
            case WARNING = 'warning';
            case DEFAULT = 'default';
        }
    @endphp
@endonce
@php
    if (empty($type)) {
    $type = AlertTypes::DEFAULT->value;
    } else if (!in_array($type, $getEnumValuesAsArray(AlertTypes::cases()))) {
    $type = 'default';
    } else {
    $type = strtolower($type);
    }

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
    'bg-green-100' => $type === AlertTypes::SUCCESS->value,
    'border-green-400' => $type === AlertTypes::SUCCESS->value,
    'text-green-700' => $type === AlertTypes::SUCCESS->value,
    'bg-red-100' => $type === AlertTypes::ERROR->value,
    'border-red-400' => $type === AlertTypes::ERROR->value,
    'text-red-700' => $type === AlertTypes::ERROR->value,
    'bg-yellow-100' => $type === AlertTypes::WARNING->value,
    'border-yellow-400' => $type === AlertTypes::WARNING->value,
    'text-yellow-700' => $type === AlertTypes::WARNING->value,
    'bg-blue-100' => $type === AlertTypes::DEFAULT->value,
    'border-blue-400' => $type === AlertTypes::DEFAULT->value,
    'text-blue-700' => $type === AlertTypes::DEFAULT->value,
    ];
@endphp
<div @class(array_merge($universalAlertCssClasses, $conditionalAlertCssClasses)) role="alert" type="{{ $type }}">
    <p class="sm:inline text-lg">
        <strong class="font-bold">{{ $heading }}</strong>
        <span class="block">{{ $message }}</span>
    </p>
</div>
