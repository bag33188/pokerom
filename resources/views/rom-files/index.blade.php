<x-app-layout>
    @foreach($romFiles as $romFile)
        <h3>{{$romFile->filename}}</h3>
        <form method="GET" action="{{route('rom-files.download', ['romFile' => $romFile])}}">
            @method('GET')
            @csrf

            <button type="submit">download!</button>
        </form>
        <!--<form method="POST" action="{{route('rom-files.destroy', ['romFile' => $romFile])}}">
            @method('DELETE')
            @csrf

            <button type="submit">delete!</button>
        </form>-->
    @endforeach
</x-app-layout>
