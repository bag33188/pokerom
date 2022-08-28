<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">
            Create a new ROM
        </h2>
    </x-slot>
    <div class="container mx-auto w-full">
        <x-jet-validation-errors class="mb-4"/>
        <form method="POST" action="{{ route('roms.store') }}">
            @csrf
            @method('POST')

            <div class="mt-2.5">
                <x-jet-label for="romName" value="Rom Name"/>
                <x-jet-input type="text" id="romName" name="rom_name"
                             class="block mt-1 w-full"
                             minlength="{{ MIN_ROM_NAME_LENGTH }}"
                             maxlength="{{ MAX_ROM_NAME_LENGTH }}"
                             required autofocus/>
            </div>
            <div class="mt-2.5">
                <label class="block font-medium text-sm text-gray-700" for="romType">ROM Type</label>
                <select @class($formSelectClasses) name="rom_type" id="romType" required autofocus>
                    @foreach($romTypes as $index => $romType)
                        <option value="{{ $romType }}">{{ strtoupper($romType) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-2.5">
                <x-jet-label for="romSize" :value="__('Rom Size')"/>
                <x-jet-input id="romSize"
                             name="rom_size"
                             data-description="rom size is measured in kibibytes (KiB)"
                             class="block mt-1 w-full"
                             type="number"
                             min="{{ MIN_ROM_SIZE }}"
                             max="{{ MAX_ROM_SIZE }}"
                             required autofocus/>
            </div>
            <div class="mt-4">
                <x-jet-button>Save!</x-jet-button>
            </div>
        </form>
    </div>
</x-app-layout>
