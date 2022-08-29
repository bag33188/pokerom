<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-lg">Pok&eacute;mon Games Library</h2>
    </x-slot>
    <div class="container mx-auto">
        <div
            class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 justify-items-center my-1.5 pb-4">
            @foreach($games as $game)
                <div class="p-6 w-full bg-white rounded-lg border border-gray-200 shadow-md">
                    <div class="w-full bg-white rounded-lg my-2.5">
                        <ul class="divide-y-2 divide-gray-100">
                            <li class="p-3 hover:bg-blue-600 hover:text-blue-200">
                                {{ $game->game_name }}&nbsp;Version
                            </li>
                            <li class="p-3 hover:bg-blue-600 hover:text-blue-200">
                                Generation {{ numberToRoman($game->generation) }}
                            </li>
                            <li class="p-3 hover:bg-blue-600 hover:text-blue-200">
                                {{ $game->region }} Region
                            </li>
                            <li class="p-3 hover:bg-blue-600 hover:text-blue-200">
                                {{ $formatGameType($game->game_type) }}
                            </li>
                            <li class="p-3 hover:bg-blue-600 hover:text-blue-200">
                                Released on {{ $game->date_released->format('F jS, Y') }}
                            </li>
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
