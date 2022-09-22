@push('styles')
    <!--suppress CssUnresolvedCustomProperty -->
    <style {!! 'type="text/css"'; !!}>
        .white-space-pre {
            white-space: pre;
        }

        .no-pointer-events {
            pointer-events: none;
        }

        .disabled-form-select {
            --disabled-outline-color: #CCC;
            --disabled-background-color: #EDEDED;
            border: 2px solid var(--disabled-outline-color) !important;
            background-color: var(--disabled-background-color) !important;
        }

        .inset-box-shadow {
            --disabled-shadow-color: rgb(211, 211, 211);
            -moz-box-shadow: inset 0 0 6px var(--disabled-shadow-color) !important;
            -webkit-box-shadow: inset 0 0 6px var(--disabled-shadow-color) !important;
            box-shadow: inset 0 0 6px var(--disabled-shadow-color) !important;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        const _CRLF_ = "\r\n";

        const uploadRomFileForm = document.forms["upload-romFile-form"];

        let tempDisableUploadBtn = () => {
            const uploadBtn = document.querySelector(
                "button[data-name=upload-romFile-btn]"
            );
            uploadBtn.classList.add("white-space-pre");
            uploadBtn.disabled = true;
            uploadBtn.textContent = `PLEASE WAIT....${_CRLF_}THIS COULD TAKE A WHILE`;
        };

        let tempDisableRomFilesFormSelect = () => {
            const romFilenameField = uploadRomFileForm["rom_filename"];

            let romFilenameDisabledClasses = [
                "no-pointer-events",
                "disabled-form-select",
                "inset-box-shadow"
            ];

            // IMPORTANT!!!
            // cannot set disabled attribute on select element since it will nullify the data being sent to the server
            // bad: romFilenameField.disabled = true;

            // remove all pointer events instead
            romFilenameField.classList.add(...romFilenameDisabledClasses);
        };

        let handleUploadRomFileForm = function () {
            uploadRomFileForm.addEventListener("submit", function () {
                tempDisableUploadBtn();
                tempDisableRomFilesFormSelect();
            });
        };
    </script>
    <script type="text/javascript">
        handleUploadRomFileForm();
    </script>
@endpush
@php
    function removeStoragePathFromFilename(string &$filename): void {
        $filename = str_replace(sprintf("%s/", ROM_FILES_DIRNAME), '', $filename);
    }

    function formatRomFilename(string $rom_filename): string {
        list($filename, $filetype) = explode('.', $rom_filename, 2);
        return sprintf("%s.%s", trim($filename), strtoupper($filetype));
    }
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">Upload a ROM File</h2>
        <h6 class="text-center">
            @if($romFilesListCount > 1)
                <span>{{ $romFilesListCount }} ROMs found in Storage</span>
            @elseif($romFilesListCount === 1)
                <span>1 ROM File found in Storage</span>
            @else
                <span>No ROMs found in Storage</span>
            @endif
        </h6>
    </x-slot>
    <div class="container mx-auto w-full">
        @unless($romFilesListCount === 0)
            <div class="p-6">
                <x-jet-validation-errors class="mb-4"/>

                <form name="upload-romFile-form"
                      method="POST"
                      action="{{ route('rom-files.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="flex flex-col">
                        <x-form-select-label class="!font-semibold" for="romFile" text="Select ROM File"/>
                        <x-form-select class="shadow" name="rom_filename" id="romFile">
                            @foreach($romFilesList as $romFileListing)
                                @php removeStoragePathFromFilename($romFileListing); @endphp
                                <option
                                    value="{{ $romFileListing }}"
                                    @selected(old('rom_filename') == $romFileListing)
                                >{{ formatRomFilename($romFileListing) }}</option>
                            @endforeach
                        </x-form-select>
                    </div>
                    <div class="my-4">
                        <x-punch-button id="submit-romFile-btn" type="submit" btn-name="upload-romFile-btn">
                            <span class="uppercase">Upload!</span>
                        </x-punch-button>
                    </div>
                </form>
            </div>
        @else
            <x-alert :alertType="\App\Enums\AlertTypeEnum::ERROR">
                <x-slot name="heading">Sorry!</x-slot>
                <x-slot name="message">No ROM Files found in
                    <samp>{{ join('/', ['storage', 'app', 'public', ROM_FILES_DIRNAME]) }}</samp> folder
                </x-slot>
            </x-alert>
        @endunless
    </div>
</x-app-layout>
