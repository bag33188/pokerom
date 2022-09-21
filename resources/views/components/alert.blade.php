@props(['heading', 'message'])
@php
    $alertCssClasses = [
      'my-6',
      'mx-4',
      'border',
      'px-4',
      'py-3',
      'rounded',
      'relative',
      'text-center',
      'bg-green-100' => $type === 'success',
      'border-green-400' => $type === 'success',
      'text-green-700' => $type === 'success',
      'bg-red-100' => $type === 'error',
      'border-red-400' => $type === 'error',
      'text-red-700' => $type === 'error',
      'bg-yellow-100' => $type === 'warning',
      'border-yellow-400' => $type === 'warning',
      'text-yellow-700' => $type === 'warning',
      'bg-blue-100' => $type === 'default',
      'border-blue-400' => $type === 'default',
      'text-blue-700' => $type === 'default',
    ];
@endphp
<div @class($alertCssClasses) role="alert" type="{{ $type }}">
    <p class="sm:inline text-lg">
        <strong class="font-bold">{{ $heading }}</strong>
        <span class="block">{{ $message }}</span>
    </p>
</div>
