<form {!! $attributes->merge(['class' => 'inline-block']) !!}
      method="POST" action="{{ route('roms.destroy', ['rom' => $rom]) }}">
    @method('DELETE')
    @csrf

    <x-jet-danger-button type="submit">Delete!</x-jet-danger-button>
</form>
