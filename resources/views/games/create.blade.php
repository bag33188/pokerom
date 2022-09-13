<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">Add Game</h2>
    </x-slot>
    <div class="container mx-auto w-full mt-3.5">
        @if($romsWithNoGame->count() > 0)
            <x-jet-validation-errors class="mb-4"/>
            <form method="POST" action="{{ route('games.store') }}">
                @method('POST')
                @csrf

                <x-form-select-label text="Assoc. ROM" for="availableRoms"/>
                <x-form-select id="availableRoms" name="rom_id" autofocus required>
                    @foreach($romsWithNoGame as $rom)
                        <option value="{{ $rom->id }}">{{ $rom->rom_name }}</option>
                    @endforeach
                </x-form-select>
                <div class="mt-2.5">
                    <x-jet-label for="gameName" :value="__('Game Name')"/>
                    <x-jet-input id="gameName" class="block mt-1 w-full" type="text"
                                 name="game_name"
                                 minlength="{{ MIN_GAME_NAME_LENGTH }}"
                                 maxlength="{{ MAX_GAME_NAME_LENGTH }}"
                                 required autofocus
                    />
                </div>
                <div class="mt-2.5">
                    <x-jet-label for="gameType" :value="__('Game Type')"/>
                    <x-form-select name="game_type" id="gameType" required autofocus>
                        <option value="" selected>Select Game Type</option>
                        @foreach(GAME_TYPES as $index => $gameType)
                            <option value="{{ $gameType }}"
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
                            >{{ ucfirst($gameRegion) }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="mt-2.5">
                    <x-jet-label for="dateReleased" :value="__('Date Released')"/>
                    <x-jet-input type="date"
                                 class="block mt-1 w-full"
                                 id="dateReleased" name="date_released" required autofocus/>
                </div>
                <div class="mt-2.5">
                    <x-jet-label for="generation" :value="__('Generation')"/>
                    <x-jet-input type="number" id="generation" name="generation"
                                 class="block mt-1 w-full"
                                 min="{{ MIN_GAME_GENERATION_VALUE }}" max="{{ MAX_GAME_GENERATION_VALUE }}" required
                                 autofocus/>
                </div>
                <div class="mt-4 w-full inline-flex flex-row justify-end">
                    <x-jet-button type="submit">
                        Save!
                    </x-jet-button>
                </div>
            </form>
        @else
            <x-alert type="error">
                <x-slot name="heading">Sorry!</x-slot>
                <x-slot name="message">There are no available roms to add a game to :(</x-slot>
            </x-alert>
        @endif
    </div>
</x-app-layout>
