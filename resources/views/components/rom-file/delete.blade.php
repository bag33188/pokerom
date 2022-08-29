<form {{ $attributes->merge(['class' => 'inline']) }} method="POST"
      action="{{ route('rom-files.destroy', ['romFile' => $romFile]) }}">
    @method('DELETE')
    @csrf

    <x-jet-danger-button type="submit">DELETE</x-jet-danger-button>
</form>
