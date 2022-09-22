@inject('romQueries', 'App\Interfaces\RomQueriesInterface')
@push('scripts')
    <script type="text/javascript">
        @verbatim
        /**
         * @name toggleContent
         * @param {boolean} contentOpened
         * @returns {boolean}
         */
        function toggleContent(contentOpened) {
            contentOpened = !contentOpened;
            return contentOpened;
        }

        /**
         * @name initROMsUiStateData
         * @returns {ROMsUIAlpineState}
         */
        function initROMsUiStateData() {
            return {
                open: true
            };
        }
        @endverbatim
    </script>
@endpush
@php
    $showHideBtnClasses = [
      'bg-emerald-500',
      'hover:bg-emerald-600',
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
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">ROMs</h2>
        <h5 class="text-center">{{ $roms->count() }}</h5>
    </x-slot:header>
    <div x-data="initROMsUiStateData()" class="mb-3 mx-3">
        <div class="w-full text-center my-2.5">
            <button type="button" @class($showHideBtnClasses) @click="open = toggleContent(open)">
                <span x-show="open">Hide</span>
                <span x-show="!open" x-cloak>Show</span>
                <span><!--&nbsp;-->ROMs</span>
            </button>
        </div>
        <x-flash-message/>
        @unless($roms->count() === 0)
            <table class="w-full test-sm text-left text-gray-800 table-auto border-2" x-show="open === true">
                <thead class="bg-gray-50">
                @for($i = 0; $i < count($htmlRomTableColumns); $i++)
                    @php $columnName = $htmlRomTableColumns[$i]; @endphp
                    <th scope="col" class="px-6 py-3">{{ $columnName }}</th>
                @endfor
                </thead>
                <tbody>
                @foreach($roms as $rom)
                    <tr class="border odd:bg-white even:bg-gray-100" data-rom-id="{{ $rom->id }}">
                        <td class="px-6 py-4">{{ $rom->rom_name }}</td>
                        <td class="px-6 py-4">{{ $romQueries->formatRomSizeSQL($rom->rom_size) }}</td>
                        <td class="px-6 py-4">{{ strtolower($rom->rom_type) }}</td>
                        <td class="px-6 py-4">
                            @if($rom->has_game)
                                <span data-game-id="{{ $rom->game->id }}">{{ $rom->game->game_name }}</span>
                            @else
                                <span>N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($rom->has_file)
                                <div class="inline-block" data-romFile-id="{{ $rom->romFile->_id }}">
                                    <x-rom-file.download :romFile="$rom->romFile"/>
                                </div>
                            @else
                                <span>No file yet!</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <x-anchor-button href="{{ route('roms.show', ['rom' => $rom]) }}">
                                Get Info!
                            </x-anchor-button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                <tr class="text-sm text-gray-700 uppercase">
                    <td class="px-6 py-3">Total Count: {{ $totalRomsCount }}</td>
                    <td class="px-6 py-3">Total Size: {{ $totalRomsSize }} Bytes</td>
                </tr>
                </tfoot>
            </table>
        @else
            <x-alert :alertType="\App\Enums\AlertTypeEnum::ERROR">
                <x-slot name="heading">Sorry!</x-slot>
                <x-slot:message>No ROMs Currently Exist in the Database</x-slot:message>
            </x-alert>
        @endunless
    </div>
</x-app-layout>
