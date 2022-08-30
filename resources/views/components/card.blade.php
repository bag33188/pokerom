@props(['heading'])
<div class="p-6 w-full bg-white rounded-lg border border-gray-200 shadow-md">
    <h5 class="mb-2 text-2xl text-center font-bold tracking-tight text-gray-900">{{ $heading }}</h5>
    <div class="w-full bg-white rounded-lg my-2.5">
        {{ $slot }}
    </div>
</div>
