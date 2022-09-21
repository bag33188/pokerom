@props(['type'])
@php
    $anchorBtnClasses = array();
    $anchorPrimaryCss = ['inline-flex', 'items-center', 'px-4', 'py-2', 'bg-gray-800', 'border', 'border-transparent', 'rounded-md', 'font-semibold', 'text-xs', 'text-white', 'uppercase', 'tracking-widest', 'hover:bg-gray-700', 'active:bg-gray-900', 'focus:outline-none', 'focus:border-gray-900', 'focus:ring', 'focus:ring-gray-300', 'disabled:opacity-25', 'transition'];
    $anchorSecondaryCss = ['inline-flex', 'items-center', 'px-4', 'py-2', 'bg-white', 'border', 'border-gray-300', 'rounded-md', 'font-semibold', 'text-xs', 'text-gray-700', 'uppercase', 'tracking-widest', 'shadow-sm', 'hover:text-gray-500', 'focus:outline-none', 'focus:border-blue-300', 'focus:ring', 'focus:ring-blue-200', 'active:text-gray-800', 'active:bg-gray-50', 'disabled:opacity-25', 'transition'];
    $anchorDangerCss = ['inline-flex', 'items-center', 'px-4', 'py-2', 'bg-gray-800', 'border', 'border-transparent', 'rounded-md', 'font-semibold', 'text-xs', 'text-white', 'uppercase', 'tracking-widest', 'hover:bg-gray-700', 'active:bg-gray-900', 'focus:outline-none', 'focus:border-gray-900', 'focus:ring', 'focus:ring-gray-300', 'disabled:opacity-25', 'transition'];
@endphp
@switch(strtolower($type ?? 'unset'))
    @case('primary')
        @php
            $anchorBtnClasses = $anchorPrimaryCss;
        @endphp
        @break
    @case('secondary')
        @php
            $anchorBtnClasses = $anchorSecondaryCss;
        @endphp
        @break
    @case('danger')
        @php
            $anchorBtnClasses = $anchorDangerCss;
        @endphp
        @break
    @default
        @php
            $anchorBtnClasses = $anchorPrimaryCss;
        @endphp
@endswitch
<a {{ $attributes->merge(['class' => joinCssClasses($anchorBtnClasses)]) }}>{{ $slot }}</a>
