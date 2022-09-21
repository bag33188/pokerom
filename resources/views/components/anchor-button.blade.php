@props(['type'])
@once
    @php
        enum AnchorTypes: string {
            case PRIMARY = 'primary';
            case SECONDARY = 'secondary';
            case DANGER = 'danger';
        }
    @endphp
@endonce
@php
    if (empty($type)) {
        $type = AnchorTypes::PRIMARY->value;
    } else if (!in_array($type, $getEnumValuesAsArray(AnchorTypes::cases()))) {
        $type = 'primary';
    } else {
        $type = strtolower($type);
    }

    $conditionalAnchorBtnClasses = [
        'bg-red-600' => $type == AnchorTypes::DANGER->value,
        'text-white' => $type == AnchorTypes::DANGER->value || $type == AnchorTypes::PRIMARY->value,
        'hover:bg-red-500' => $type == AnchorTypes::DANGER->value,
        'active:bg-red-600' => $type == AnchorTypes::DANGER->value,
        'border-transparent' => $type == AnchorTypes::DANGER->value || $type == AnchorTypes::PRIMARY->value,
        'focus:ring-red-200' => $type == AnchorTypes::DANGER->value,
        'focus:border-red-700' => $type == AnchorTypes::DANGER->value,
        'bg-white' => $type == AnchorTypes::SECONDARY->value,
        'shadow-sm' => $type == AnchorTypes::SECONDARY->value,
        'text-gray-700' => $type == AnchorTypes::SECONDARY->value,
        'border-gray-300' => $type == AnchorTypes::SECONDARY->value,
        'active:bg-gray-50' => $type == AnchorTypes::SECONDARY->value,
        'hover:text-gray-500' => $type == AnchorTypes::SECONDARY->value,
        'focus:ring-blue-200' => $type == AnchorTypes::SECONDARY->value,
        'active:text-gray-800' => $type == AnchorTypes::SECONDARY->value,
        'focus:border-blue-300' => $type == AnchorTypes::SECONDARY->value,
        'hover:bg-gray-700' => $type == AnchorTypes::PRIMARY->value,
        'active:bg-gray-900' => $type == AnchorTypes::PRIMARY->value,
        'focus:ring-gray-300' => $type == AnchorTypes::PRIMARY->value,
        'focus:border-gray-900' => $type == AnchorTypes::PRIMARY->value,
        'bg-gray-800' => $type == AnchorTypes::PRIMARY->value,
    ];

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
@endphp
<a {{ $attributes }} @class(array_merge($conditionalAnchorBtnClasses, $universalAnchorCssClasses))>{{ $slot }}</a>
