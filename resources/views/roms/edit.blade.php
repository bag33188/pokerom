<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">
            Edit {{ $rom->rom_name }}
        </h2>
    </x-slot>
    <div class="container mx-auto w-full">
        <x-jet-validation-errors class="mb-4"/>
        <form method="POST" action="{{ route('roms.update', ['rom' => $rom]) }}">
            @csrf
            @method('PUT')

            <div class="mt-2.5">
                <x-jet-label for="romName" value="ROM Name"/>
                <x-jet-input type="text" id="romName" name="rom_name"
                             class="block mt-1 w-full"
                             :value="$rom->rom_name"
                             minlength="{{ MIN_ROM_NAME_LENGTH }}"
                             maxlength="{{ MAX_ROM_NAME_LENGTH }}"
                             required autofocus/>
            </div>
            <div class="mt-2.5">
                <x-form-select-label for="romType" text="ROM Type"/>
                <x-form-select name="rom_type" id="romType" required autofocus>
                    @foreach(ROM_TYPES as $index => $romType)
                        <option
                            value="{{ $romType }}"
                            @selected(strtolower($rom->rom_type) == $romType)
                        >{{ strtoupper($romType) }}</option>
                    @endforeach
                </x-form-select>
            </div>
            <div class="mt-2.5">
                <x-jet-label for="romSize" value="ROM Size"/>
                <x-jet-input id="romSize"
                             name="rom_size"
                             :value="$rom->rom_size"
                             placeholder="Note: `rom_size` is measured in kibibytes (KiB)"
                             class="block mt-1 w-full"
                             type="number"
                             min="{{ MIN_ROM_SIZE }}"
                             max="{{ MAX_ROM_SIZE }}"
                             required autofocus/>
            </div>
            <div class="mt-4 flex flex-row justify-between">
                <x-jet-button type="submit">Update!</x-jet-button>
                <x-anchor-button :btn-type="\App\Enums\AnchorButtonTypeEnum::SECONDARY"
                                 href="{{ route('roms.show', ['rom' => $rom]) }}">
                    Cancel
                </x-anchor-button>
            </div>
        </form>
    </div>
</x-app-layout>
