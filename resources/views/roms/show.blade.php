@inject('romFileRepository', 'App\Interfaces\RomFileRepositoryInterface')
@inject('romQueries', 'App\Interfaces\RomQueriesInterface')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">{{ $rom->rom_name }} Information</h2>
    </x-slot>
    <div class="py-6 px-5">
        <x-list-group>
            <x-list-item>ROM ID: {{ $rom->id }}</x-list-item>
            <x-list-item>ROM Name: {{ $rom->rom_name }}</x-list-item>
            <x-list-item>ROM Size: {{ $romQueries->formatRomSizeSQL($rom->rom_size) }}</x-list-item>
            <x-list-item>ROM Type: {{ $rom->rom_type }}</x-list-item>
            @if($rom->has_game)
                <x-list-item><p class="mt-1.5 inline-block">Game Info</p>
                    <x-list-group class="border-0 py-2">
                        <x-list-item class="border-0">Game ID: {{ $rom->game->id }}</x-list-item>
                        <x-list-item>Game Name: {{ $rom->game->game_name }}</x-list-item>
                        <x-list-item>Region: {{ $rom->game->region }}</x-list-item>
                        <x-list-item class="border-0">Release
                            Date: {{ $rom->game->date_released->format('l, F jS, Y') }}</x-list-item>
                    </x-list-group>
                </x-list-item>
            @endif
            @if($rom->has_file)
                <x-list-item><p class="mt-1.5 inline-block">File Info</p>
                    <x-list-group class="border-0 py-2">
                        <x-list-item class="border-0">File ID: {{ $rom->romFile->_id }}</x-list-item>
                        <x-list-item>File Name: {{ $rom->romFile->filename }}</x-list-item>
                        <x-list-item>Designated
                            Console: {{ $romFileRepository->determineConsole($rom->romFile) }}</x-list-item>
                        <x-list-item>File Length: {{ $rom->romFile->length }} Bytes</x-list-item>
                        <x-list-item class="border-0">MD5 Hash: {{ $rom->romFile->md5 }}</x-list-item>
                    </x-list-group>
                </x-list-item>
            @endif
        </x-list-group>
        @if(auth()->user()->isAdmin())
            <div class="flex flex-row justify-between">
                <div class="mt-3">
                    <form method="POST" action="{{ route('roms.destroy', ['rom' => $rom]) }}">
                        @method('DELETE')
                        @csrf

                        <x-jet-danger-button type="submit">Delete!</x-jet-danger-button>
                    </form>
                </div>
                <div class="mt-3">
                    <x-anchor-button :href="route('roms.edit', ['rom' => $rom])">
                        Edit!
                    </x-anchor-button>
                </div>
            </div>
        @else
            <div class="mt-3">
                <x-anchor-button class="float-left" type="secondary" :href="route('roms.index')">Go Back!
                </x-anchor-button>
            </div>
        @endif
    </div>
</x-app-layout>
