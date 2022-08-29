@php
    $btnClassesStr = <<<'EOS'
    class="inline-flex items-center px-4 py-2 bg-blue-700 border
    border-transparent rounded-md font-semibold text-xs text-white
    uppercase tracking-widest hover:bg-blue-800 active:bg-blue-800
    focus:outline-none focus:border-blue-600 focus:ring focus:ring-blue-300
    disabled:opacity-25 transition"
    EOS;
    $tileClassesStr = <<<'EOS'
    class="border border-gray-200 bg-white shadow-md rounded w-full h-full
    inline-grid grid-cols-1 grid-rows-[auto_auto] gap-y-2 justify-self-center p-2"
    EOS;

    $eosRegExp = /** @lang RegExp */ "/(?:([\r\n]+)|((?:\s{2,8})|\t+))/";
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-lg">Pok&eacute;mon ROM Files</h2>
    </x-slot>
    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-4 mx-4 mb-4 mt-1 items-center">
        @foreach($romFiles as $romFile)
            <div {!! preg_replace($eosRegExp, _SPACE, $tileClassesStr) !!}>
                <div class="p-2 border rounded-md shadow-inner border-gray-300 flex flex-col">
                    <p class="inline-block">Filename: {{ $romFile->filename }}</p>
                    <p class="inline-block">Filesize: {{ $romFile->length }} Bytes</p>
                    <p class="inline-block">Uploaded On: {{ $formatUploadDate($romFile->uploadDate) }}</p>
                    <div class="mt-2 inline-flex flex-row justify-between">
                        <form class="inline" method="GET"
                              action="{{route('rom-files.download', ['romFile' => $romFile])}}">
                            @method('GET')
                            @csrf

                            <x-jet-button type="submit">Download</x-jet-button>
                        </form>
                        <form class="inline" method="POST"
                              action="{{route('rom-files.destroy', ['romFile' => $romFile])}}">
                            @method('DELETE')
                            @csrf

                            <x-jet-danger-button type="submit">DELETE</x-jet-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
