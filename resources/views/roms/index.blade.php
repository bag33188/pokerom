<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-gray-900">ROMs</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($roms as $rom)
                <h3>{{$rom->rom_name}}</h3>
            @endforeach
        </div>
    </div>
</x-app-layout>
