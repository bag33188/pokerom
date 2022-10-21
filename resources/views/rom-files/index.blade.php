@inject('romFileQueries', 'App\Interfaces\RomFileQueriesInterface')
@php
    $showHideBtnClasses = [
      'bg-rose-500',
      'hover:bg-rose-600',
      'text-white',
      'font-bold',
      'py-2',
      'px-4',
      'shadow-md',
      'rounded'
    ];
@endphp
<x-app-layout>
    <x-slot:header>
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">Pok&eacute;mon ROM Files</h2>
        <h5 class="text-center">{{ $romFiles->count() }} Total ROM Files</h5>
    </x-slot:header>
    <div class="container m-auto p-0.5" x-data="{{ strip_quotes(collect(['open' => true])->toJson()) }}">
        <x-flash-message/>
        @unless($romFiles->count() < 1)
            <div class="w-full text-center my-2.5">
                <button type="button" @class($showHideBtnClasses) @click="open = !open">
                    <span x-show="open">Hide</span>
                    <span x-show="!open" x-cloak>Show</span>
                    <span>ROM Files</span>
                </button>
            </div>
            <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-4 mx-4 mb-4 mt-3.5 items-center"
                 x-show="open === true">
                @foreach($romFiles as $romFile)
                    <x-tile :data-romFile-id="$romFile->_id" class="justify-self-center">
                        <p class="inline-block">File ID: {{ $romFile->_id }}</p>
                        <p class="inline-block">Filename: {{ $romFile->filename }}</p>
                        <p class="inline-block">Filesize: {{ $romFile->length }} Bytes</p>
                        <p class="inline-block">
                            Uploaded
                            On: {{ $romFileQueries->formatUploadDate($romFile->uploadDate, ...array_values($uploadDateParams)) }}
                        </p>
                        <p class="inline-block">
                            @if($romFile->rom)
                                <span class="font-semibold">ROM ID: {{ $romFile->rom->id }}</span>
                            @else
                                <span class="font-semibold"
                                      title="This ROM File does not yet have a ROM resource linked to it.">
                                    No Assoc. ROM
                                </span>
                            @endif
                        </p>
                        <div class="mt-2 flex flex-row justify-between">
                            <x-rom-file.download class="order-0" :romFile="$romFile"/>
                            <x-anchor-button class="order-1" :href="route('rom-files.show', ['romFile' => $romFile])">
                                Info!
                            </x-anchor-button>
                        </div>
                    </x-tile>
                @endforeach
            </div>
        @else
            <x-alert :alertType="App\Enums\AlertTypeEnum::ERROR" heading="Sorry!" message="No ROM Files in database"/>
        @endunless
    </div>
</x-app-layout>
