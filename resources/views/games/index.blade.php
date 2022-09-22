@inject('gameQueries', 'App\Interfaces\GameQueriesInterface')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">Pok&eacute;mon Games Library</h2>
    </x-slot>
    <div class="container mx-auto py-6">
        @unless($games->count() < 1)
            <div
                class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 justify-items-center my-1.5 pb-4">
                @foreach($games as $game)
                    <x-card :game="$game">
                        <x-slot:heading>{{ $game->game_name }}</x-slot:heading>
                        <x-list-group class="divide-y-2 divide-gray-100 border">
                            <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200 !border-b-0">
                                {{ $game->game_name }} Version
                            </x-list-item>
                            <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200">
                                Generation {{ numberToRoman($game->generation) }}
                            </x-list-item>
                            <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200">
                                {{ $game->region }} Region
                            </x-list-item>
                            <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200">
                                {{ $gameQueries->formatGameTypeSQL($game->game_type) }}
                            </x-list-item>
                            <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200">
                                Released on {{ $game->date_released->format('F jS, Y') }}
                            </x-list-item>
                        </x-list-group>
                        <div class="my-2 flex flex-row justify-center">
                            <x-anchor-button
                                :btn-type="\App\Enums\AnchorButtonTypeEnum::PRIMARY"
                                :href="route('games.show', ['game' => $game])">
                                Get Info!
                            </x-anchor-button>
                        </div>
                    </x-card>
                @endforeach
            </div>
        @else
            <x-alert :alertType="\App\Enums\AlertTypeEnum::ERROR">
                <x-slot:heading>Sorry!</x-slot:heading>
                <x-slot:message>No Games in database</x-slot:message>
            </x-alert>
        @endunless
    </div>
</x-app-layout>
