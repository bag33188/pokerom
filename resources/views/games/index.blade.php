@inject('gameQueries', 'App\Interfaces\GameQueriesInterface')
@php
    $showHideBtnClasses = [
      'bg-sky-500',
      'hover:bg-sky-600',
      'text-white',
      'font-bold',
      'py-2',
      'px-4',
      'shadow-md',
      'rounded'
    ];
@endphp
<x-app-layout>
    <x-slot:header>
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">Pok&eacute;mon Games Library</h2>
        <h5 class="text-center">{{ $games->count() }} Total Games</h5>
    </x-slot:header>
    <div class="container mx-auto my-3.5" x-data="{{ strip_quotes(collect(['open' => true])->toJson()) }}">
        <x-flash-message/>
        @unless($games->count() < 1)
            <div class="w-full text-center my-2.5">
                <button type="button" @class($showHideBtnClasses) @click="open = !open">
                    <span x-show="open">Hide</span>
                    <span x-show="!open" x-cloak>Show</span>
                    <span>Games</span>
                </button>
            </div>
            <div
                class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4 justify-items-center my-1.5 pb-4"
                x-show="open === true">
                @foreach($games as $game)
                    <x-card :data-game-id="$game->id" :heading="$game->game_name">
                        <div class="flex flex-col h-full">
                            <x-list-group class="divide-y-2 divide-gray-100 border">
                                <x-list-item class="p-3 hover:bg-blue-600 hover:text-blue-200 !border-b-0">
                                    {!! $game->game_name !!} Version
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
                                    :btn-type="App\Enums\AnchorButtonTypeEnum::PRIMARY"
                                    :href="route('games.show', ['game' => $game])">
                                    Get Info!
                                </x-anchor-button>
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>
        @else
            <x-alert :alertType="App\Enums\AlertTypeEnum::ERROR" heading="Sorry!" message="No Games in database"/>
        @endunless
    </div>
</x-app-layout>
