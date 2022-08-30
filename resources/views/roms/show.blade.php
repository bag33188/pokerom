<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">{{ $rom->rom_name }} Information</h2>
    </x-slot>
    <div class="py-6 px-5">
        <x-list-group>
            <x-list-item>ROM ID: {{ $rom->id }}</x-list-item>
            <x-list-item>ROM Name: {{ $rom->rom_name }}</x-list-item>
            <x-list-item>ROM Size: {{ $formatRomSize($rom->rom_size) }}</x-list-item>
            <x-list-item>ROM Type: {{ $rom->rom_type }}</x-list-item>
            @if($rom->has_game)
                <x-list-item>Game ID: {{ $rom->game->id }}</x-list-item>
                <x-list-item>Game Name: {{ $rom->game->game_name }}</x-list-item>
                <x-list-item>Region: {{ $rom->game->region }}</x-list-item>
                <x-list-item>Release Date: {{ $rom->game->date_released->format('l, F jS, Y') }}</x-list-item>
            @endif
            @if($rom->has_file)
                <x-list-item>File ID: {{ $rom->romFile->_id }}</x-list-item>
                <x-list-item>File Name: {{ $rom->romFile->filename }}</x-list-item>
                <x-list-item>File Length: {{ $rom->romFile->length }} Bytes</x-list-item>
                <x-list-item>MD5 Hash: {{ $rom->romFile->md5 }}</x-list-item>
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
                    <x-anchor-button href="{{ route('roms.edit', ['rom' => $rom]) }}">
                        Edit!
                    </x-anchor-button>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
