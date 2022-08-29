<form {{ $attributes->merge(['class' => 'inline']) }} method="GET"
      action="{{ route('rom-files.download', ['romFile' => $romFile]) }}">
    @method('GET')
    @csrf

    <x-jet-button type="submit">Download</x-jet-button>
</form>
