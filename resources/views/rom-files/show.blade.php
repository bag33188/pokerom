@inject('romFileRepository', 'App\Interfaces\RomFileRepositoryInterface')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">
            {{ $romFile->filename }} Information
        </h2>
    </x-slot>
    <div class="my-4 mx-3.5">
        <div class='flex flex-col'>
            <div class="order-0">
                <x-list-group class="shadow">
                    <x-list-item>File ID: {{ $romFile->_id }}</x-list-item>
                    <x-list-item>Filename: <code>{{ $romFile->filename }}</code></x-list-item>
                    <x-list-item>Designated Console: <b>{{ $romFileRepository->determineConsole($romFile) }}</b>
                    </x-list-item>
                    <x-list-item>Content Length:
                        <data value="{{ $romFile->length }}">{{ $romFile->length }} Bytes</data>
                    </x-list-item>
                    <x-list-item>Content Chunk Size:
                        <data value="{{ $romFile->chunkSize }}">{{ $romFile->getChunkSizeInBits() }} Bits</data>
                    </x-list-item>
                    <x-list-item>Date Uploaded:
                        <time
                            datetime="{{ $romFileRepository->formatUploadDate($romFile->uploadDate, DATE_W3C, 'GMT') }}">{{ $romFileRepository->formatUploadDate($romFile->uploadDate, 'm-d-Y, h:i:s A (T, I)', 'PST8PDT') }}</time>
                    </x-list-item>
                    <x-list-item>Content Hash (MD5):
                        <data value="{{ strtoupper($romFile->md5) }}">{{ $romFile->md5 }}</data>
                    </x-list-item>
                </x-list-group>
            </div>
            <div class="order-1 mt-2.5 inline-flex flex-row justify-between">
                @if($userIsAdmin)
                    <div class="inline-block order-1">

                        <x-rom-file.delete class="order-1" :romFile="$romFile"/>
                    </div>
                @else
                    <div class="inline-block order-1">
                        <x-anchor-button :btn-type="\App\Enums\AnchorButtonTypeEnum::SECONDARY"
                                         :href="route('rom-files.index')">
                            Go Back!
                        </x-anchor-button>
                    </div>
                @endif
                <div class="inline-block order-0">
                    <x-rom-file.download :romFile="$romFile"/>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
