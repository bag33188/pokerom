<form {{ $attributes->merge(['class' => 'inline']) }} method="GET"
      action="{{ route('rom-files.download', ['romFile' => $romFile]) }}"
      name="download-romFile-{{ $romFile->_id }}-form"
      enctype="multipart/form-data">
    @method('GET')
    @csrf
    <x-jet-button type="submit" id="download-romFile-{{ $romFile->_id }}-btn">Download</x-jet-button>
</form>
