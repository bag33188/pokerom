@php
    $anchorBtnClasses = [];
@endphp
@switch($btnType)
    @case('primary')
        @php
            $anchorBtnClasses = $anchorPrimary;
        @endphp
        @break
    @case('secondary')
        @php
            $anchorBtnClasses = $anchorSecondary;
        @endphp
        @break
    @case('danger')
        @php
            $anchorBtnClasses = $anchorDanger;
        @endphp
        @break
    @default
        @php
            $anchorBtnClasses =  $anchorPrimary;
        @endphp
@endswitch
<a {{ $attributes->merge(['class' => joinCssClasses($anchorBtnClasses)]) }}>{{ $slot }}</a>
