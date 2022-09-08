@inject('romFileRepository', 'App\Interfaces\RomFileRepositoryInterface')
@inject('romQueries', 'App\Interfaces\RomQueriesInterface')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900"
            {!! $rom->has_game === TRUE ? 'title="' . $rom->game->game_name . '"' : '' !!}>
            {{ $rom->rom_name }} Information
        </h2>
    </x-slot>
    <div class="py-6 px-5">
        <x-list-group class="shadow" x-data="{ romInfoOpened: true, gameInfoOpened: true, romFileInfoOpened: true }">
            <x-list-item class="pb-4"><p class="mt-1.5 mb-3.5 inline-block font-semibold cursor-pointer"
                                         @click="romInfoOpened = !romInfoOpened">ROM
                    Info</p>
                <x-list-group class="!border-1 rounded-lg -border-gray-200 bg-gray-100 shadow-inner"
                              x-show="romInfoOpened === true">
                    <x-list-item>ROM ID: {{ $rom->id }}</x-list-item>
                    <x-list-item>ROM Name: {{ $rom->rom_name }}</x-list-item>
                    <x-list-item>ROM Size: {{ $romQueries->formatRomSizeSQL($rom->rom_size) }}</x-list-item>
                    <x-list-item class="-border-b border-b-0">ROM Type: {{ $rom->rom_type }}</x-list-item>
                </x-list-group>
                <p class="font-bold m-0 p-0 text-xl" x-show="!romInfoOpened">...</p>
            </x-list-item>
            @if($rom->has_game)
                <x-list-item class="pb-4"><p class="mt-1.5 mb-3.5 inline-block font-semibold cursor-pointer"
                                             @click="gameInfoOpened = !gameInfoOpened">Game Info</p>
                    <x-list-group class="!border-1 rounded-lg -border-gray-200 bg-gray-100 shadow-inner"
                                  x-show="gameInfoOpened === true">
                        <x-list-item>Game ID: {{ $rom->game->id }}</x-list-item>
                        <x-list-item>Game Name: {{ $rom->game->game_name }} Version</x-list-item>
                        <x-list-item>Region: {{ $rom->game->region }}</x-list-item>
                        <x-list-item>Generation: {{ numberToRoman($rom->game->generation) }}</x-list-item>
                        <x-list-item class="-border-b border-b-0">Release
                            Date: {{ $rom->game->date_released->format('l, F jS, Y') }}</x-list-item>
                    </x-list-group>
                    <p class="font-bold m-0 p-0 text-xl" x-show="!gameInfoOpened">...</p>
                </x-list-item>
            @endif
            @if($rom->has_file)
                <x-list-item class="pb-4"><p class="mt-1.5 mb-3.5 inline-block font-semibold cursor-pointer"
                                             @click="romFileInfoOpened = !romFileInfoOpened">File Info</p>
                    <x-list-group class="!border-1 rounded-lg -border-gray-200 bg-gray-100 shadow-inner"
                                  x-show="romFileInfoOpened === true">
                        <x-list-item>File ID: {{ $rom->romFile->_id }}</x-list-item>
                        <x-list-item>File Name: {{ $rom->romFile->filename }}</x-list-item>
                        <x-list-item>File Length: {{ $rom->romFile->length }} Bytes</x-list-item>
                        <x-list-item class="-border-b border-b-0">Designated
                            Console: {{ $romFileRepository->determineConsole($rom->romFile) }}</x-list-item>
                    </x-list-group>
                    <p class="font-bold m-0 p-0 text-xl" x-show="!romFileInfoOpened">...</p>
                </x-list-item>
            @endif
        </x-list-group>
        @if(auth()->user()->isAdmin())
            <div class="flex flex-row justify-between">
                <div class="mt-3">
                    @include('roms.delete', ['rom' => $rom])
                </div>
                <div class="mt-3 inline-flex flex-row">
                    <x-anchor-button @class(["order-2" => !$rom->has_file]) :href="route('roms.edit', ['rom' => $rom])">
                        Edit!
                    </x-anchor-button>
                    @if(!$rom->has_file)
                        <div class="mx-1 order-1"></div>
                        <form class="order-0" method="POST" action="{{ route('roms.link-file', ['rom' => $rom]) }}">
                            @method('PATCH')
                            @csrf
                            <x-jet-button type="submit">Link Rom To File If Exists</x-jet-button>
                        </form>
                    @endif
                </div>
            </div>
        @else
            <div class="mt-3">
                <x-anchor-button class="float-left" type="secondary" :href="route('roms.index')">
                    Go Back!
                </x-anchor-button>
            </div>
        @endif
    </div>
</x-app-layout>
