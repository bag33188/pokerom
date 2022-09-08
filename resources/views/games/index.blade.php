@inject('gameQueries', 'App\Interfaces\GameQueriesInterface')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">Pok&eacute;mon Games Library</h2>
    </x-slot>
    <div class="container mx-auto py-6">
        @unless(count($games) < 1)
            <div
                class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 justify-items-center my-1.5 pb-4">
                @foreach($games as $game)
                    <x-card :game="$game" :heading="$game->game_name">
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
                            <x-anchor-button :href="route('games.show', ['game' => $game])">
                                Get Info!
                            </x-anchor-button>
                        </div>
                    </x-card>
                @endforeach
            </div>
        @else
            <div class="my-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center"
                 role="alert">
                <p class="sm:inline text-lg">
                    <strong class="font-bold">Sorry!</strong>
                    <span class="block">No Games in database</span>
                </p>
            </div>
        @endunless
    </div>
</x-app-layout>
