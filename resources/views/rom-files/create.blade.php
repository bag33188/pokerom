@php
    function removeStorageBasePathFromString(string $value): string {
        return str_replace(ROM_FILES_DIRNAME . '/', '', $value);
    }
@endphp
<x-app-layout>
    <form method="POST" action="{{route('rom-files.store')}}">
        @csrf
        @method('POST')

        <label for="romFile">rom file</label>
        <select name="rom_filename" id="romFile">
            @for($i = 0; $i < count($romFilesList); $i++)
                <option
                    value="{{removeStorageBasePathFromString($romFilesList[$i])}}">{{removeStorageBasePathFromString($romFilesList[$i])}}</option>
            @endfor
        </select>
        <x-jet-button>upload!</x-jet-button>
    </form>
</x-app-layout>
