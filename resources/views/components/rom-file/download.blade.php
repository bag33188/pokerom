<form {{ $attributes->merge(['class' => 'inline']) }} method="GET"
      action="{{ route('rom-files.download', ['romFile' => $romFile]) }}"
      name="download-romFile-{{ $romFile->_id }}-form"
      enctype="multipart/form-data">
    @method('GET')
    @csrf

    <x-jet-button type="submit" id="download-romFile-{{ $romFile->_id }}-btn"
                  class="inline-flex flex-row justify-between gap-2.5">
        <span class="order-0">Download</span>
        <span class="order-1">@include('partials._download-icon')</span>
    </x-jet-button>
</form>
