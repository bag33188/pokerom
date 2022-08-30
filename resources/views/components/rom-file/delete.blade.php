@once
    @push('styles')
        <style {!! 'type="text/css"' !!}>
            .not-allowed {
                cursor: not-allowed !important;
            }
        </style>
    @endpush
    @push('scripts')
        <script type="text/javascript">
            /**
             * Disabled the delete button on the client
             * while the server is in the process of deleting the rom file.
             *
             * @param {string} deleteBtnId
             */
            function disableDeleteBtn(deleteBtnId) {
                const deleteBtn = document.getElementById(deleteBtnId);
                deleteBtn.disabled = true;
                deleteBtn.classList.add("not-allowed");
            }
        </script>
    @endpush
@endonce
@php
    $deleteRomFileBtnId = sprintf("delete-romFile-%s-btn", $romFile->_id);
@endphp
<form {{ $attributes->merge(['class' => 'inline']) }} method="POST"
      action="{{ route('rom-files.destroy', ['romFile' => $romFile]) }}"
      name="delete-romFile-{{ $romFile->_id }}-form"
      onsubmit="disableDeleteBtn({{ Js::from($deleteRomFileBtnId) }})">
    @method('DELETE')
    @csrf

    <x-jet-danger-button type="submit" id="{{ $deleteRomFileBtnId }}">DELETE!</x-jet-danger-button>
</form>
