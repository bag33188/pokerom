@inject('romFileRepository', 'App\Interfaces\RomFileRepositoryInterface')
<x-app-layout>
    <x-slot:header>
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">Pok&eacute;mon ROM Files</h2>
        <h5 class="text-center">{{ $romFiles->count() }}</h5>
    </x-slot:header>
    <div class="container m-auto p-0.5">
        <x-flash-message/>
        @unless($romFiles->count() < 1)
            <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-4 mx-4 mb-4 mt-3.5 items-center">
                @foreach($romFiles as $romFile)
                    <x-tile class="justify-self-center">
                        <p class="inline-block">File ID: {{ $romFile->_id }}</p>
                        <p class="inline-block">Filename: {{ $romFile->filename }}</p>
                        <p class="inline-block">Filesize: {{ $romFile->length }} Bytes</p>
                        <p class="inline-block">
                            @php
                                $uploadDateFormattingParams = [
                                    $romFile->uploadDate,
                                    'm-d-Y, h:i:s A (T, I)',
                                    'America/Los_Angeles'
                                ];
                            @endphp
                            Uploaded On: {{ $romFileRepository->formatUploadDate(...$uploadDateFormattingParams) }}
                        </p>
                        <p class="inline-block">
                            @php
                                $romIdHtml = null;
                                if ($romFile->rom) {
                                    $romIdHtml = "<span class=\"font-semibold\">{$romFile->rom->getKey()}</span>";
                                } else {
                                    $noAssocRomTitle = 'This ROM File does not yet have a ROM resource linked to it.';
                                    $romIdHtml = "<span class=\"font-semibold\" title=\"$noAssocRomTitle\">No Assoc. ROM</span>";
                                }
                            @endphp
                            <span>ROM ID:&nbsp;{!! $romIdHtml !!}</span>
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
            <x-alert :alertType="\App\Enums\AlertTypeEnum::ERROR" heading="Sorry!" message="No ROM Files in database"/>
        @endunless
    </div>
</x-app-layout>
