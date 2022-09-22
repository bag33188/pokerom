@inject('romFileRepository', 'App\Interfaces\RomFileRepositoryInterface')
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">
            {{ $romFile->filename }} Information
        </h2>
    </x-slot>
    <div class="my-4 mx-3.5">
        <div class='grid grid-cols-2 grid-rows-[auto_minmax(min-content,_auto)] gap-y-2'>
            <div class="row-span-1 col-span-full">
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
                        <time datetime="{{ $cpuUploadDate }}">{{ $readableUploadDate }}</time>
                    </x-list-item>
                    <x-list-item>Content Hash (MD5):
                        <data value="{{ strtoupper($romFile->md5) }}"><code>{{ $romFile->md5 }}</code></data>
                    </x-list-item>
                </x-list-group>
            </div>
            <div class="row-start-2 row-end-3 col-start-2 col-end-3 justify-self-end">
                @if($userIsAdmin)
                    <x-rom-file.delete :romFile="$romFile"/>
                @else
                    <x-anchor-button :btn-type="\App\Enums\AnchorButtonTypeEnum::SECONDARY"
                                     :href="route('rom-files.index')">
                        Go Back!
                    </x-anchor-button>
                @endif
            </div>
            <div class="row-start-2 row-end-3 col-span-1 justify-self-start">
                <x-rom-file.download :romFile="$romFile"/>
            </div>
        </div>
    </div>
</x-app-layout>
