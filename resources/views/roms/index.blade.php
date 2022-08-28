@push('styles')
    <style {!! 'type="text/css"'; !!}>
        [x-cloak] {
            display: none;
        }
    </style>
@endpush
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-gray-900">ROMs</h2>
    </x-slot>
    <div class="py-12">
        <table class="w-full test-sm text-left text-gray-800 table-auto">
            <thead class="bg-gray-50">
            @foreach($tableColumns as $columnName)
                <th scope="col" class="px-6 py-3">{{ $columnName }}</th>
            @endforeach
            </thead>
            <tbody>
            @foreach($roms as $rom)
                <tr class="border odd:bg-white even:bg-gray-100">
                    <td class="px-6 py-4">{{ $rom->rom_name }}</td>
                    <td class="px-6 py-4">{{ $formatRomSize($rom->rom_size) }}</td>
                    <td class="px-6 py-4">{{ strtolower($rom->rom_type) }}</td>
                    <td class="px-6 py-4">
                        @if($rom->has_game)
                            <span>{{ $rom->game->game_name }}</span>
                        @else
                            <span>N/A</span>
                        @endif</td>
                    <td class="px-6 py-4">
                        @if($rom->has_file)
                            <form class="inline" method="GET" action="{{
                                route('rom-files.download', ['romFile' => $rom->romFile])
                            }}">
                                @method('GET')
                                @csrf

                                <x-jet-button type="submit">Download!</x-jet-button>
                            </form>
                        @else
                            <span>No file yet!</span>
                        @endif
                    </td>
                    <td class="px-6 py-4"></td>
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
    </div>
</x-app-layout>
