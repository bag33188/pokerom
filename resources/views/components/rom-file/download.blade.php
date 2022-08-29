<form {{ $attributes->merge(['class' => 'inline']) }} method="GET"
      action="{{ route('rom-files.download', ['romFile' => $romFile]) }}"
      name="download-rom-file-{{ $romFile->id }}{{-- -form --}}"
      enctype="multipart/form-data">
    @method('GET')
    @csrf
    <x-jet-button type="submit">Download</x-jet-button>
</form>
