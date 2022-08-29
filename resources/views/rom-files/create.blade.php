@php
    $formSelectClasses = [
        'border-gray-300',
        'focus:border-indigo-300',
        'focus:ring',
        'focus:ring-indigo-200',
        'focus:ring-opacity-50',
        'rounded-md',
        'shadow-sm',
        'block',
        'mt-1',
        'w-full'
    ];

    function removeStoragePathFromFilename(string $value): string {
        return str_replace(ROM_FILES_DIRNAME . '/', '', $value);
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

        .bg-html-white-smoke {
            background-color: #F5F5F5;
        }

        .border-crimson-solid {
            border: 2px solid #DC143C !important;
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
                "bg-html-white-smoke",
                "border-crimson-solid"
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
                        <label class="block font-medium text-sm text-gray-700" for="romFile">Select ROM File</label>
                        <select @class($formSelectClasses) name="rom_filename" id="romFile">
                            @for($i = 0; $i < count($romFilesList); $i++)
                                @php $romFileItem = removeStoragePathFromFilename($romFilesList[$i]); @endphp
                                <option value="{{ $romFileItem }}">{{ $romFileItem }}</option>
                            @endfor
                        </select>
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
