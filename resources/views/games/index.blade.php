<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-lg">Pok&eacute;mon Games Library</h2>
    </x-slot>
    <div class="container mx-auto py-6">
        <div
            class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 justify-items-center my-1.5 pb-4">
            @foreach($games as $game)
                <x-card :game="$game" title="{{ $game->game_name }}">
                    <x-list-group class="divide-y-2 divide-gray-100">
                        <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200">
                            {{ $game->game_name }}&nbsp;Version
                        </x-list-item>
                        <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200">
                            Generation {{ numberToRoman($game->generation) }}
                        </x-list-item>
                        <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200">
                            {{ $game->region }} Region
                        </x-list-item>
                        <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200">
                            {{ $formatGameType($game->game_type) }}
                        </x-list-item>
                        <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200">
                            Released on {{ $game->date_released->format('F jS, Y') }}
                        </x-list-item>
                    </x-list-group>
                </x-card>
            @endforeach
        </div>
    </div>
</x-app-layout>
