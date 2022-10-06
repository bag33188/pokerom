<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <div class="flex flex-row w-full justify-between">
                <span class="order-0">{{ __('Dashboard') }}</span>
                <span class="order-1">Welcome, {{ $userFirstName }}!</span>
            </div>
        </h2>
    </x-slot>

    <div data-name="dashboard-container"
         class="px-1.5 mx-1.5 py-1.5 my-1.5 sm:my-2 sm:py-2 md:my-4 md:py-4 lg:my-6 lg:py-6 xl:py-16 xl:my-16">
        <div data-name="dash-content-card" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-jet-welcome/>
        </div>
    </div>
</x-app-layout>
