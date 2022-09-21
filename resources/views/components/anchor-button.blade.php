@props(['type'])
@php
    if(empty($type)) {
        $type = 'primary';
    }
    $anchorBtnClasses = [
        'bg-red-600' => $type == 'danger',
        'text-white' => $type == 'danger' || $type == 'primary',
        'hover:bg-red-500' => $type == 'danger',
        'active:bg-red-600' => $type == 'danger',
        'border-transparent' => $type == 'danger' || $type == 'primary',
        'focus:ring-red-200' => $type == 'danger',
        'focus:border-red-700' => $type == 'danger',
        'bg-white' => $type == 'secondary',
        'shadow-sm' => $type == 'secondary',
        'text-gray-700' => $type == 'secondary',
        'border-gray-300' => $type == 'secondary',
        'active:bg-gray-50' => $type == 'secondary',
        'hover:text-gray-500' => $type == 'secondary',
        'focus:ring-blue-200' => $type == 'secondary',
        'active:text-gray-800' => $type == 'secondary',
        'focus:border-blue-300' => $type == 'secondary',
        'hover:bg-gray-700' => $type == 'primary',
        'active:bg-gray-900' => $type == 'primary',
        'focus:ring-gray-300' => $type == 'primary',
        'focus:border-gray-900' => $type == 'primary',
        'bg-gray-800' => $type == 'primary',
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
<a {{ $attributes }} @class($anchorBtnClasses)>{{ $slot }}</a>
