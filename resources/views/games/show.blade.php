@php
    $userIsAdmin = auth()->user()->isAdmin();
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-lg">{{ $game->game_name }} Information</h2>
    </x-slot>
    <div class="p-2.5">
        <div class="w-full h-full grid grid-cols-2 grid-rows-[minmax(0,_1fr)_auto] gap-y-4">
            <x-list-group @class([
                'row-end-3' => ! $userIsAdmin,
                'row-end-1' => $userIsAdmin,
                'row-start-1',
                'col-span-full',
                'shadow'
            ])>
                <x-list-item>{{ $game->game_name }} Version</x-list-item>
                <x-list-item title="{{ $game->generation }}">
                    Generation {{ numberToRoman($game->generation) }}</x-list-item>
                <x-list-item>{{ $game->region }} Region</x-list-item>
                <x-list-item>{{ $formatGameType($game->game_type) }}</x-list-item>
                <x-list-item style="border-bottom: 0 !important;">Released
                    on: {{ $game->date_released->format('l, F jS, Y') }}</x-list-item>
            </x-list-group>
            @if($userIsAdmin)
                <div class="row-start-2 row-end-2 ml-1 col-start-2 col-end-2 justify-self-end">
                    <form class="inline" action="{{ route('games.destroy', ['game' => $game]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <x-jet-danger-button type="submit">DELETE!</x-jet-danger-button>
                    </form>
                </div>
                <div class="col-start-1 col-end-1 row-start-2 row-end-2 justify-self-start">
                    <div class="my-2">
                        <x-jet-responsive-nav-link href="{{ route('games.edit', ['game' => $game]) }}">Edit!
                        </x-jet-responsive-nav-link>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
