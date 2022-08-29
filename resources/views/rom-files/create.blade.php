@php
    function removeStoragePathFromFilename(string $value): string {
        return str_replace(sprintf("%s/", ROM_FILES_DIRNAME), '', $value);
    }
@endphp
@push('styles')
    <style {!! 'type="text/css"'; !!}>
        .white-space-pre {
            white-space: pre;
        }

        .no-pointer-events {
            pointer-events: none;
        }

        .disabled-form-select {
            --disabled-outline: #CCC;
            --disabled-background: #EDEDED;
            border: 2px solid var(--disabled-outline) !important;
            background-color: var(--disabled-background) !important;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript">
        const _CRLF_ = "\r\n";

        const uploadRomFileForm = document.forms["upload-romFile-form"];

        let tempDisableUploadBtn = () => {
            const uploadBtn = document.querySelector(
                "button[data-name=submit-romFile-btn]"
            );
            uploadBtn.classList.add("white-space-pre");
            uploadBtn.disabled = true;
            uploadBtn.textContent = `PLEASE WAIT....${_CRLF_}THIS COULD TAKE A WHILE`;
        };

        let tempDisableRomFilesFormSelect = () => {
            const romFilenameField = uploadRomFileForm["rom_filename"];

            let romFilenameDisabledClasses = [
                "no-pointer-events",
                'disabled-form-select'
            ];

            // IMPORTANT!!!
            // cannot set disabled attribute on select element since it will nullify the data being sent to the server
            // bad: romFilenameField.disabled = true;

            // remove all pointer events instead
            romFilenameField.classList.add(...romFilenameDisabledClasses);
        }

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
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl text-center font-semibold leading-tight text-gray-900">Upload a ROM File</h2>
    </x-slot>
    <div class="container mx-auto w-full">
        @unless(count($romFilesList) === 0)
            <div class="p-6">
                <x-jet-validation-errors class="mb-4"/>

                <form name="upload-romFile-form" method="POST" action="{{ route('rom-files.store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="flex flex-col">
                        <x-form-select-label for="romFile" text="Select ROM File"/>
                        <x-form-select name="rom_filename" id="romFile">
                            @for($i = 0; $i < count($romFilesList); $i++)
                                @php $romFileItem = removeStoragePathFromFilename($romFilesList[$i]); @endphp
                                <option value="{{ $romFileItem }}">{{ $romFileItem }}</option>
                            @endfor
                        </x-form-select>
                    </div>
                    <div class="my-4">
                        @include("ui.punch-button", ['btn_name' => 'submit-romFile-btn'])
                    </div>
                </form>
            </div>
        @else
            <h2 class="text-center text-lg mt-7">No ROM Files found in <samp>{{ ROM_FILES_DIRNAME }}</samp> folder</h2>
        @endunless
    </div>
</x-app-layout>
