@inject('romFileRepository', 'App\Interfaces\RomFileRepositoryInterface')
@inject('romQueries', 'App\Interfaces\RomQueriesInterface')
@push('styles')
    @env('local') <!--suppress CssUnusedSymbol, CssUnknownProperty, SpellCheckingInspection --> @endenv
    <style {!! 'type="text/css"'; !!}>
        .no-select {
            -webkit-touch-callout: none; /* iOS Safari */
            -webkit-user-select: none; /* Safari */
            -khtml-user-select: none; /* Konqueror HTML */
            -moz-user-select: none; /* Old versions of Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none;
            /* Non-prefixed version, currently supported by Chrome, Edge, Opera and Firefox */
        }

        @if($userIsAdmin)
        @media (max-width: 433px) {
            #link-rom-{{ $rom->id }}-container {
                margin-bottom: 0.75rem;
                margin-right: 0 !important;
            }
        }
        @endif
    </style>
@endpush
@push('scripts')
    @verbatim
        <script type="text/javascript">
            /**
             * @name toggleInfo
             * @param {boolean} infoOpened
             * @returns {boolean}
             */
            function toggleInfo(infoOpened) {
                infoOpened = !infoOpened;
                return infoOpened;
            }
        </script>
    @endverbatim
@endpush
@php
    $innerListGroupClasses = [
      'border-1',
      'rounded-lg',
      'bg-gray-100',
      'shadow-inner' => !$userIsAdmin,
    ];
    $paragraphSectionClasses = ["mt-1.5", "mb-3.5", "inline-block", "font-semibold", "cursor-pointer", "no-select"];
    $paragraphHiddenContentClasses = ['font-bold', 'text-gray-400', 'text-xl', 'm-0', 'p-0'];

    $toggleROMInfo = Js::from('romInfoOpened = toggleInfo(romInfoOpened)');
    $toggleGameInfo = Js::from('gameInfoOpened = toggleInfo(gameInfoOpened)');
    $toggleROMFileInfo = Js::from('romFileInfoOpened = toggleInfo(romFileInfoOpened)');
@endphp
<x-app-layout>
    <x-slot:header>
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900" {!! $gameNameTitle !!}>
            {{ $rom->rom_name }} Information
        </h2>
    </x-slot:header>

    <div class="py-6 px-5">

        <x-list-group @class(['shadow', 'no-select' => !$userIsAdmin]) x-data="{{ $alpineInitialUiState }}">
            <x-list-item class="pb-4">
                <p @class($paragraphSectionClasses) @click={{ $toggleROMInfo }}>ROM Info</p>
                <x-list-group @class($innerListGroupClasses) x-show="romInfoOpened === true">
                    <x-list-item>ROM ID: {{ $rom->id }}</x-list-item>
                    <x-list-item>ROM Name: {{ $rom->rom_name }}</x-list-item>
                    <x-list-item>ROM Size: {{ $romQueries->formatRomSizeSQL($rom->rom_size) }}</x-list-item>
                    <x-list-item class="border-b-0">ROM Type: {{ $rom->rom_type }}</x-list-item>
                </x-list-group>
                <p @class($paragraphHiddenContentClasses) x-show="!romInfoOpened" x-cloak>...</p>
            </x-list-item> <!-- // end of ROM Info -->
            @if($rom->has_game)
                <x-list-item class="pb-4">
                    <p @class($paragraphSectionClasses) @click={{ $toggleGameInfo }}>Game Info</p>
                    <x-list-group @class($innerListGroupClasses) x-show="gameInfoOpened === true">
                        <x-list-item>Game ID: {{ $rom->game->id }}</x-list-item>
                        <x-list-item>Game Name: {{ $rom->game->game_name }} Version</x-list-item>
                        <x-list-item>Region: {{ $rom->game->region }}</x-list-item>
                        <x-list-item>Generation: {{ numberToRoman($rom->game->generation) }}</x-list-item>
                        <x-list-item class="border-b-0">
                            Release Date: {{ $rom->game->date_released->format('l, F jS, Y') }}
                        </x-list-item>
                    </x-list-group>
                    <p @class($paragraphHiddenContentClasses) x-show="!gameInfoOpened" x-cloak>...</p>
                </x-list-item>
            @endif <!-- // end of Game Info -->
            @if($rom->has_file)
                <x-list-item class="pb-4">
                    <p @class($paragraphSectionClasses) @click={{ $toggleROMFileInfo }}>
                        File Info
                    </p>
                    <x-list-group @class($innerListGroupClasses) x-show="romFileInfoOpened === true">
                        <x-list-item>File ID: {{ $rom->romFile->_id }}</x-list-item>
                        <x-list-item>File Name: {{ $rom->romFile->filename }}</x-list-item>
                        <x-list-item>File Length: {{ $rom->romFile->length }} Bytes</x-list-item>
                        <x-list-item class="border-b-0">
                            Designated Console: {{ $romFileRepository->determineConsole($rom->romFile) }}
                        </x-list-item>
                    </x-list-group>
                    <p @class($paragraphHiddenContentClasses) x-show="!romFileInfoOpened" x-cloak>...</p>
                </x-list-item>
            @endif <!-- // end of ROM File Info -->
        </x-list-group>

        @if($userIsAdmin)
            <div class="flex flex-row justify-between no-select">
                <div class="order-0 mt-3">
                    <x-rom.delete :rom="$rom" />
                </div>
                <div
                    class="order-1 mt-3 inline-flex flex-row-reverse justify-start space-x-2.5 space-x-reverse w-full flex-wrap-reverse">
                    <div class="order-0">
                        <x-anchor-button :href="route('roms.edit', ['rom' => $rom])">
                            Edit!
                        </x-anchor-button>
                    </div>
                    @if(!$rom->has_file)
                        <div class="order-1" id="link-rom-{{ $rom->id }}-container">
                            <form
                                class="inline-block"
                                method="POST"
                                name="link-rom-{{ $rom->id }}-form"
                                action="{{ route('roms.link-file', ['rom' => $rom]) }}">
                                @method('PATCH')
                                @csrf

                                <x-jet-button type="submit">Link Rom To File If Exists</x-jet-button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <div class="mt-3 no-select flex flex-row justify-between w-full">
                <div class="order-1">
                    <x-anchor-button :btn-type="\App\Enums\AnchorButtonTypeEnum::SECONDARY" :href="route('roms.index')">
                        Go Back
                    </x-anchor-button>
                </div>
                <div class="order-0">
                    <x-rom-file.download :romFile="$rom->romFile" :title="$rom->romFile->filename" />
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
