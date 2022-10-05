<form {!! $attributes->merge(['class' => 'inline-block']) !!}
      method="POST"
      action="{{ route('games.destroy', ['game' => $game]) }}">
    @csrf
    @method('DELETE')

    <x-jet-danger-button type="submit">DELETE!</x-jet-danger-button>
</form>
