@inject('gameQueries', 'App\Interfaces\GameQueriesInterface')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">
            {{ $game->game_name }} Information
        </h2>
    </x-slot>
    <div class="p-2.5">
        <div class="grid grid-cols-2 grid-rows-[minmax(min-content,_1fr)_auto] gap-y-2.5">
            <x-list-group class="row-span-1 col-span-full shadow">
                <x-list-item>{{ $game->game_name }} Version</x-list-item>
                <x-list-item>Generation {{ numberToRoman($game->generation) }}</x-list-item>
                <x-list-item>{{ $game->region }} Region</x-list-item>
                <x-list-item>Game Type: {{ $gameQueries->formatGameTypeSQL($game->game_type) }}</x-list-item>
                <x-list-item>Released on: {{ $game->date_released->format('l, F jS, Y') }}</x-list-item>
                <x-list-item class="!border-b-0">
                    <p>ROM: <a class="underline"
                               href="{{ route('roms.show', ['rom' => $game->rom]) }}"
                               title="{{ $game->rom->id }}">{{ $game->rom->rom_name }}</a></p>
                </x-list-item>
            </x-list-group>
            @if($userIsAdmin)
                <div class="row-start-2 row-end-3 col-start-1 col-end-2 justify-self-start">
                    <x-anchor-button :btn-type="App\Enums\AnchorButtonTypeEnum::PRIMARY"
                                     :href="route('games.edit', ['game' => $game])">
                        Edit!
                    </x-anchor-button>
                </div>
                <div class="row-start-2 row-end-3 col-start-2 col-end-3 justify-self-end">
                    <x-game.delete :game="$game"/>
                </div>
            @else
                <div class="row-start-2 row-end-3 col-start-2 col-end-3 justify-self-end">
                    <x-anchor-button :btn-type="App\Enums\AnchorButtonTypeEnum::SECONDARY"
                                     :href="route('games.index')">
                        Go Back
                    </x-anchor-button>
                </div>
                <div class="row-start-2 row-end-3 col-start-1 col-end-2 justify-self-start">
                    <x-anchor-button :href="route('roms.show', ['rom' => $game->rom])">
                        Goto ROM Info
                    </x-anchor-button>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
