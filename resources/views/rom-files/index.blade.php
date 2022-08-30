<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-lg">Pok&eacute;mon ROM Files</h2>
        <h6 class="text-center font-semibold">{{ sizeof($romFiles) }}</h6>
    </x-slot>
    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-4 mx-4 mb-4 mt-1 items-center">
        @foreach($romFiles as $romFile)
            <x-tile class="justify-self-center">
                <p class="inline-block">Filename: {{ $romFile->filename }}</p>
                <p class="inline-block">Filesize: {{ $romFile->length }} Bytes</p>
                <p class="inline-block">Uploaded On: {{ $formatUploadDate($romFile->uploadDate) }}</p>
                <p class="inline-block">
                    ROM ID: {!!
                        $romFile->rom
                            ? '<span class="font-bold">' . $romFile->rom->getKey() . "</span>"
                            : '<span class="font-semibold">No Assoc. ROM</span>'
                            !!}
                </p>
                @if(auth()->user()->isAdmin())
                    <div class="mt-2 inline-flex flex-row justify-between">
                        <x-rom-file.download :romFile="$romFile"/>
                        <x-rom-file.delete :romFile="$romFile"/>
                    </div>
                @endif
            </x-tile>
        @endforeach
    </div>
</x-app-layout>
