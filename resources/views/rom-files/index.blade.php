<x-app-layout>
    @foreach($romFiles as $romFile)
        <div class="py-3 px-3 border-2">

            <h3>{{$romFile->filename}}</h3>
            <form method="GET" action="{{route('rom-files.download', ['romFile' => $romFile])}}">
                @method('GET')
                @csrf

                <x-jet-button type="submit">download!</x-jet-button>
            </form>
            <form method="POST" action="{{route('rom-files.destroy', ['romFile' => $romFile])}}">
                @method('DELETE')
                @csrf

                <x-jet-danger-button type="submit">DELETE!</x-jet-danger-button>
            </form>
        </div>
    @endforeach
</x-app-layout>
