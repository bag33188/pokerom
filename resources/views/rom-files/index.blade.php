<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-lg">Pok&eacute;mon ROM Files</h2>
    </x-slot>
    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-4 mx-4 mb-4 mt-1 items-center">
        @foreach($romFiles as $romFile)
            <x-tile class="justify-self-center">
                <p class="inline-block">Filename: {{ $romFile->filename }}</p>
                <p class="inline-block">Filesize: {{ $romFile->length }} Bytes</p>
                <p class="inline-block">Uploaded On: {{ $formatUploadDate($romFile->uploadDate) }}</p>
                <div class="mt-2 inline-flex flex-row justify-between">
                    <form class="inline" method="GET"
                          action="{{ route('rom-files.download', ['romFile' => $romFile]) }}">
                        @method('GET')
                        @csrf

                        <x-jet-button type="submit">Download</x-jet-button>
                    </form>
                    <form class="inline" method="POST"
                          action="{{ route('rom-files.destroy', ['romFile' => $romFile]) }}">
                        @method('DELETE')
                        @csrf

                        <x-jet-danger-button type="submit">DELETE</x-jet-danger-button>
                    </form>
                </div>
            </x-tile>
        @endforeach
    </div>
</x-app-layout>
