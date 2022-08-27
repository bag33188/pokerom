<x-app-layout>
    @foreach($romFiles as $romFile)
        <h3>{{$romFile->filename}}</h3>
    @endforeach
</x-app-layout>
