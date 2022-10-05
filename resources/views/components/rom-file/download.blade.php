<form {!! $attributes->merge(['class' => 'inline-block']) !!}
      method="GET"
      action="{{ route('rom-files.download', ['romFile' => $romFile]) }}"
      name="download-romFile-{{ $romFile->_id }}-form"
      enctype="multipart/form-data">
    @method('GET')
    @csrf

    <x-jet-button type="submit" id="download-romFile-{{ $romFile->_id }}-btn"
                  class="flex flex-row justify-between space-x-2 lg:space-x-3">
        {{-- `lg` is @media (min-width: 1024px) --}}
        <span class="order-0">Download</span>
        <span class="order-1">@include('partials._download-icon')</span>
    </x-jet-button>
</form>
