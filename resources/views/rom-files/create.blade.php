<x-app-layout>
    <form method="POST" action="{{route('rom-files.store')}}">
        @csrf
        @method('POST')

        <label for="romFile">rom file</label>
        <select name="rom_filename" id="romFile">
            @for($i = 0; $i < count($romFilesList); $i++)
                @php $romFileItem = str_replace(ROM_FILES_DIRNAME . '/', '', $romFilesList[$i]); @endphp
                <option value="{{$romFileItem}}">{{$romFileItem}}</option>
            @endfor
        </select>
        <x-jet-button>upload!</x-jet-button>
    </form>
</x-app-layout>
