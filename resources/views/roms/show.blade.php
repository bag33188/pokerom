<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">{{ $rom->rom_name }} Information</h2>
    </x-slot>
    <div class="py-12 px-10">
        <ul class="bg-white rounded-lg border border-gray-200 text-gray-900">
            <li class="px-6 py-2 border-b border-gray-200 w-full">Rom ID: {{ $rom->id }}</li>
            <li class="px-6 py-2 border-b border-gray-200 w-full">Rom Name: {{ $rom->rom_name }}</li>
            <li class="px-6 py-2 border-b border-gray-200 w-full">Rom Size: {{ $formatRomSize($rom->rom_size) }}</li>
            <li class="px-6 py-2 border-b border-gray-200 w-full">Rom Type: {{ $rom->rom_type }}</li>
            @if($rom->has_game)
                <li class="px-6 py-2 border-b border-gray-200 w-full">Game ID: {{ $rom->game->id }}</li>
                <li class="px-6 py-2 border-b border-gray-200 w-full">Game Name: {{ $rom->game->game_name }}</li>
                <li class="px-6 py-2 border-b border-gray-200 w-full">Region: {{ $rom->game->region }}</li>
                <li class="px-6 py-2 border-b border-gray-200 w-full">Release Date: {{ $rom->game->date_released->format('l, F jS, Y') }}</li>
            @endif
            @if($rom->has_file)
                <li class="px-6 py-2 border-b border-gray-200 w-full">File ID: {{ $rom->romFile->_id }}</li>
                <li class="px-6 py-2 border-b border-gray-200 w-full">File Name: {{ $rom->romFile->filename }}</li>
                <li class="px-6 py-2 border-b border-gray-200 w-full">File Length: {{ $rom->romFile->length }} Bytes</li>
                <li class="px-6 py-2 border-b border-gray-200 w-full">MD5 Hash: {{ $rom->romFile->md5 }}</li>
            @endif
        </ul>
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
                    <x-jet-responsive-nav-link href="{{ route('roms.edit', ['rom' => $rom]) }}">Edit!</x-jet-responsive-nav-link>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
