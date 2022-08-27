<x-app-layout>
    <form method="POST" action="{{ route('roms.store') }}">
        @csrf
        @method('POST')

        <div class="mt-2.5">
            <x-jet-label for="romName" value="Rom Name" />
            <x-jet-input id="romName" name="rom_name" type="text"   minlength="{{MIN_ROM_NAME_LENGTH}}"
                         maxlength="{{MAX_ROM_NAME_LENGTH}}"
                         required autofocus />
        </div>
    </form>
</x-app-layout>
