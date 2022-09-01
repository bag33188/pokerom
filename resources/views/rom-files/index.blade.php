<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">Pok&eacute;mon ROM Files</h2>
        <h6 class="text-center font-semibold">{{ sizeof($romFiles) }}</h6>
    </x-slot>
    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-4 mx-4 mb-4 mt-1 items-center">
        @foreach($romFiles as $romFile)
            <x-tile class="justify-self-center">
                <p class="inline-block">File ID: {{ $romFile->_id }}</p>
                <p class="inline-block">Filename: {{ $romFile->filename }}</p>
                <p class="inline-block">Filesize: {{ $romFile->length }} Bytes</p>
                <p class="inline-block">Uploaded On:
                    {{ $formatUploadDate($romFile->uploadDate, 'm-d-Y, h:i:s A (T, I)', 'America/Los_Angeles') }}</p>
                <p class="inline-block">
                    ROM ID: {!!
                                $romFile->rom
                                    ? '<span class="font-bold">' . $romFile->rom->getKey() . "</span>"
                                    : '<span class="font-semibold">No Assoc. ROM</span>'
                            !!}
                </p>
                <div class="mt-2 inline-flex flex-row justify-between">
                    <x-rom-file.download :romFile="$romFile"/>
                    <x-anchor-button href="{{ route('rom-files.show', ['romFile' => $romFile]) }}">
                        Info!
                    </x-anchor-button>
                </div>
            </x-tile>
        @endforeach
    </div>
</x-app-layout>
