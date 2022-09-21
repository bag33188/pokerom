<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">Edit {{ $game->game_name }}
            Version</h2>
    </x-slot>
    <div class="p-3">
        <x-jet-validation-errors class="mb-4"/>
        <form method="POST" action="{{ route('games.update', ['game' => $game]) }}">
            @method('PUT')
            @csrf

            <div class="mt-2.5">
                <x-jet-label for="gameName" :value="__('Game Name')"/>
                <x-jet-input id="gameName" class="block mt-1 w-full" type="text"
                             name="game_name"
                             :value="$replaceEacuteWithE($game->game_name)"
                             minlength="{{ MIN_GAME_NAME_LENGTH }}"
                             maxlength="{{ MAX_GAME_NAME_LENGTH }}"
                             required autofocus
                />
            </div>
            <div class="mt-2.5">
                <x-jet-label for="gameType" :value="__('Game Type')"/>
                <x-form-select name="game_type" id="gameType" required autofocus>
                    @foreach(GAME_TYPES as $index => $gameType)
                        <option value="{{ $gameType }}"
                            @selected(strtolower($game->game_type) == $gameType)
                        >{{ str_capitalize($gameType, true, '-', 2) }}</option>
                    @endforeach
                </x-form-select>
            </div>
            <div class="mt-2.5">
                <x-jet-label for="gameRegion" :value="__('Region')"/>
                <x-form-select
                    name="region" id="gameRegion"
                    required autofocus>
                    @foreach(REGIONS as $index => $gameRegion)
                        <option value="{{ $gameRegion }}"
                            @selected(strtolower($game->region) == $gameRegion)
                        >{{ ucfirst($gameRegion) }}</option>
                    @endforeach
                </x-form-select>
            </div>
            <div class="mt-2.5">
                <x-jet-label for="dateReleased" :value="__('Date Released')"/>
                <x-jet-input type="date"
                             :value="$game->date_released->format('Y-m-d')"
                             class="block mt-1 w-full"
                             id="dateReleased" name="date_released" required autofocus/>
            </div>
            <div class="mt-2.5">
                <x-jet-label for="generation" :value="__('Generation')"/>
                <x-jet-input type="number" id="generation" name="generation"
                             :value="$game->generation"
                             class="block mt-1 w-full"
                             min="{{ MIN_GAME_GENERATION_VALUE }}" max="{{ MAX_GAME_GENERATION_VALUE }}" required
                             autofocus/>
            </div>
            <div class="mt-4 inline-flex flex-row justify-between w-full">
                <div class="order-0">
                    <x-jet-button type="submit">
                        Save!
                    </x-jet-button>
                </div>
                <div class="order-1 inline-block">
                    <x-anchor-button type="secondary" href="{{ route('games.show', ['game' => $game]) }}">
                        Cancel
                    </x-anchor-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
