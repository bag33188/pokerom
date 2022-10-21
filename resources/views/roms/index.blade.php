@inject('romQueries', 'App\Interfaces\RomQueriesInterface')
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

    $htmlRomTableColumns = ['ROM Name', 'ROM Size', 'ROM Type', 'Game Name', 'Download', 'Information'];

    $romsWithFilesCount = $romQueries->getCountOfRomsThatHaveROMFiles();

    function strip_quotes(string $value): string {
        return (string)preg_replace("/([\x{22}\x{27}])|(\x{26}(?:quot|apos|\x{23}0?3[94])\x{3B})/iu", '', $value);
    }
@endphp
<x-app-layout>
    <x-slot:header>
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">ROMs</h2>
        @unless($userIsAdmin)
            <h5 class="text-center">{{ $totalRomsCount }} Total ROMs</h5>
        @endunless
    </x-slot:header>
    <div x-data="{!! strip_quotes(collect(['open' => true])->toJson(JSON_FORCE_OBJECT)) !!}" class="mb-3 mx-3">
        <x-flash-message/>
        @unless($totalRomsCount === 0)
            <div class="w-full text-center my-2.5">
                <button type="button" @class($showHideBtnClasses) @click="open = !open">
                    <span x-show="open">Hide</span>
                    <span x-show="!open" x-cloak>Show</span>
                    <span><!--&nbsp;-->ROMs</span>
                </button>
            </div>
            <table class="w-full test-sm text-left text-gray-800 table-auto border-2" x-show="open === true">
                <thead class="bg-gray-50">
                @for($i = 0; $i < count($htmlRomTableColumns); $i++)
                    @php $columnName = $htmlRomTableColumns[$i]; @endphp
                    <th scope="col" class="px-6 py-3">{{ $columnName }}</th>
                @endfor
                </thead>
                <tbody>
                @foreach($roms as $rom)
                    <tr data-rom-id="{{ $rom->id }}" class="border odd:bg-white even:bg-gray-100">
                        <td class="px-6 py-4">{{ $rom->rom_name }}</td>
                        <td class="px-6 py-4">{{ $romQueries->formatRomSizeSQL($rom->rom_size) }}</td>
                        <td class="px-6 py-4">{{ strtolower($rom->rom_type) }}</td>
                        <td class="px-6 py-4">
                            @if($rom->has_game)
                                <span data-game-id="{{ @$rom->game->id }}">{{ @$rom->game->game_name }}</span>
                            @else
                                <span>N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($rom->has_file)
                                <div class="inline-block" data-romFile-id="{{ @$rom->romFile->_id }}">
                                    <x-rom-file.download :romFile="$rom->romFile"/>
                                </div>
                            @else
                                <span>No file yet!</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <x-anchor-button :href="route('roms.show', ['rom' => $rom])">
                                Get Info!
                            </x-anchor-button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                <tr class="text-sm text-gray-700 uppercase">
                    <td class="px-6 py-3">
                        Total Count: {{ $totalRomsCount }}
                        <br/>
                        w/ Files: {{ $romsWithFilesCount }}
                    </td>
                    <td class="px-6 py-3">Total Size: {{ $totalRomsSize }} Bytes</td>
                </tr>
                </tfoot>
            </table>
        @else
            <x-alert :alertType="App\Enums\AlertTypeEnum::ERROR"
                     heading="Sorry!"
                     message="No ROMs Currently Exist in the Database"/>
        @endunless
    </div>
</x-app-layout>
