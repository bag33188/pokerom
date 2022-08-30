<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">{{ $romFile->filename }}
            Information</h2>
    </x-slot>
    <div class="my-4 mx-3.5">
        @php
            function determineConsole(\App\Models\RomFile $romFile): string {
                $fileType = $romFile->getRomFileType(includeFullStop: false);
                return match ($fileType) {
                    'GB' => 'Gameboy',
                    'GBC' => 'Gameboy Color',
                    'GBA' => 'Gameboy Advance',
                    'NDS' => 'Nintendo DS',
                    '3DS' => 'Nintendo 3DS',
                    'XCI' => 'Nintendo Switch',
                    default => 'Unknown',
                };
            }
        @endphp
        <div class='flex flex-col'>
            <div class="order-0">
                <x-list-group>
                    <x-list-item>Filename: {{ $romFile->filename }}</x-list-item>
                    <x-list-item>Designated Console: <span class="font-bold">{{ determineConsole($romFile) }}</span>
                    </x-list-item>
                    <x-list-item>Date Uploaded: {{ $formatUploadDate($romFile->uploadDate) }}</x-list-item>
                    <x-list-item>Content Hash (MD5): {{ $romFile->md5 }}</x-list-item>
                    <x-list-item>Content Length: {{ $romFile->length }} Bytes</x-list-item>
                    <x-list-item>Content Chunk Size: {{ $romFile->chunkSize * 8 }} Bites</x-list-item>
                </x-list-group>
            </div>
            <div class="order-1 mt-2.5 inline-flex flex-row justify-between">
                <div class="inline-block order-1">
                    @if($userIsAdmin)
                        <x-rom-file.delete class="order-1" :romFile="$romFile"/>
                    @else
                        <x-anchor-button class="order-1" type="secondary" href="{{ route('rom-files.index') }}">
                            Go Back!
                        </x-anchor-button>
                    @endif
                </div>
                <div class="inline-block order-0">
                    <x-rom-file.download :romFile="$romFile"/>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
