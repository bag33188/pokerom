<form class="inline-block" action="{{ route('games.destroy', ['game' => $game]) }}" method="POST">
    @csrf
    @method('DELETE')
    <x-jet-danger-button type="submit">DELETE!</x-jet-danger-button>
</form>
