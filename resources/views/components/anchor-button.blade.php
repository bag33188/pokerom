@props(['type'])
@php
    $anchorBtnClasses = [];
@endphp
@switch($type)
    @case('primary')
        @php
            $anchorBtnClasses = $anchorPrimaryClasses;
        @endphp
        @break
    @case('secondary')
        @php
            $anchorBtnClasses = $anchorSecondaryClasses;
        @endphp
        @break
    @case('danger')
        @php
            $anchorBtnClasses = $anchorDangerClasses;
        @endphp
        @break
    @default
        @php
            $anchorBtnClasses =  $anchorPrimaryClasses;
        @endphp
@endswitch
<a {{ $attributes->merge(['class' => joinCssClasses($anchorBtnClasses)]) }}>{{ $slot }}</a>
