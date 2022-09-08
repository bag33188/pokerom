@props(['heading', 'message'])
<div class="my-6 mx-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center"
     role="alert" type="{{ $type }}">
    <p class="sm:inline text-lg">
        <strong class="font-bold">{{ $heading }}</strong>
        <span class="block">{{ $message }}</span>
    </p>
</div>
