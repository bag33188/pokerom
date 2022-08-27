<x-app-layout>
@php
   var_export( Storage::disk('public')->files('rom_files'));
@endphp
</x-app-layout>
