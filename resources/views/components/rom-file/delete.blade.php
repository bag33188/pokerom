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
            function disableDeleteBtn(deleteBtnId) {
                const deleteBtn = document.getElementById(deleteBtnId);
                deleteBtn.disabled = true;
                deleteBtn.classList.add("not-allowed");
            }
        </script>
    @endpush
@endonce
@php
    $deleteBtnId = sprintf("delete-romFile-btn-%s", $romFile->id);
@endphp
<form {{ $attributes->merge(['class' => 'inline']) }} method="POST"
      action="{{ route('rom-files.destroy', ['romFile' => $romFile]) }}"
      onsubmit="disableDeleteBtn('{{$deleteBtnId}}')">
    @method('DELETE')
    @csrf

    <x-jet-danger-button type="submit" id="{{ $deleteBtnId }}">DELETE</x-jet-danger-button>
</form>
