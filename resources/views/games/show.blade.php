@inject('gameQueries', 'App\Interfaces\GameQueriesInterface')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">
            {{ $game->game_name }} Information
        </h2>
    </x-slot>
    <div class="p-2.5">
        <div class="w-full h-full grid grid-cols-2 grid-rows-[minmax(0,_1fr)_auto] gap-y-4">
            <x-list-group class="row-end-1 row-start-1 col-span-full shadow">
                <x-list-item>{{ $game->game_name }} Version</x-list-item>
                <x-list-item>
                    Generation <span title="{{ $game->generation }}">{{ numberToRoman($game->generation) }}</span>
                </x-list-item>
                <x-list-item>{{ $game->region }} Region</x-list-item>
                <x-list-item>Game Type: {{ $gameQueries->formatGameTypeSQL($game->game_type) }}</x-list-item>
                <x-list-item>Released
                    on: {{ $game->date_released->format('l, F jS, Y') }}</x-list-item>
                <x-list-item class="!border-b-0">
                    ROM: <span title="{{ $game->rom->id }}">{{ $game->rom->rom_name }}</span>
                </x-list-item>
            </x-list-group>
            @if($userIsAdmin)
                <div class="row-start-2 row-end-2 ml-1 col-start-2 col-end-2 justify-self-end">
                    @include('games.delete', ['game' => $game])
                </div>
                <div class="col-start-1 col-end-1 row-start-2 row-end-2 justify-self-start">
                    <x-anchor-button :btn-type="\App\Enums\AnchorButtonTypeEnum::PRIMARY" :href="route('games.edit', ['game' => $game])">
                        Edit!
                    </x-anchor-button>
                </div>
            @else
                <div class="w-full col-span-full flex flex-row justify-between">
                    <div class="inline-block order-0">
                        <x-anchor-button :btn-type="\App\Enums\AnchorButtonTypeEnum::PRIMARY" :href="route('games.index')">
                            Go Back
                        </x-anchor-button>
                    </div>
                    <div class="inline-block order-1">
                        <x-anchor-button :href="route('roms.show', ['rom' => $game->rom])">
                            Goto ROM Info
                        </x-anchor-button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
