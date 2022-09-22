@php
    use App\Enums\FlashMessageTypeEnum as FlashMessageTypes;
@endphp
@pushOnce('scripts')
    <script type="text/javascript">
        @verbatim
        /**
         * @name flashMessageData
         * @returns {FlashMessageObject}
         */
        function flashMessageData() {
            return {
                show_flash_message: true,
            };
        }

        /**
         * @name flashMessageIsVisible
         * @param {boolean} show_flash_message
         * @returns {boolean}
         */
        const flashMessageIsVisible = (show_flash_message) => show_flash_message === true;
        @endverbatim
    </script>
@endPushOnce
@php
    $successCssClasses = ['w-full', 'text-center', 'bg-green-100', 'border', 'border-green-400', 'text-green-700', 'px-3', 'py-2', 'rounded', 'relative'];
    $errorCssClasses = ['w-full', 'text-center', 'bg-red-100', 'border', 'border-red-400', 'text-red-700', 'px-3', 'py-2', 'rounded', 'relative'];
    $notificationCssClasses = ['w-full', 'text-center', 'border', 'bg-blue-100', 'border-blue-400', 'text-blue-600', 'px-3', 'py-2', 'rounded', 'relative'];
    $defaultCssClasses = ['w-full', 'text-center', 'bg-gray-200', 'border', 'border-gray-400', 'text-gray-600', 'px-3', 'py-2', 'rounded', 'relative'];

    $flashMessageStyles = null;

    match (strtoupper(Session::get('message-type'))) {
        FlashMessageTypes::SUCCESS->name => $flashMessageStyles = $successCssClasses,
        FlashMessageTypes::ERROR->name => $flashMessageStyles = $errorCssClasses,
        FlashMessageTypes::NOTIFICATION->name => $flashMessageStyles = $notificationCssClasses,
        default => $flashMessageStyles = $defaultCssClasses,
    };
@endphp
@if(Session::has('message'))
    <div {{ $attributes }}
         x-data="flashMessageData()"
         x-show="flashMessageIsVisible(show_flash_message)"
         x-init="setTimeout(() => (show_flash_message = false), {{ session('timeout') ?? Js::from(5000) }})"
    >
        <div class="mx-3.5 my-3">
            <div
                @class($flashMessageStyles)
                role="alert" type="{{ strtolower(Session::get('message-type')) ?? 'default' }}">
                <button type="button"
                        @click="show_flash_message = false"
                        class="-mt-1 text-red-500 float-right clear-none text-2xl">
                    &times;
                </button>
                @switch(strtoupper(Session::get('message-type')))
                    @case(FlashMessageTypes::SUCCESS->name)
                        <h2 class="font-bold text-xl">{{ session('header') ?? 'Success!' }}</h2>
                        @break
                    @case(FlashMessageTypes::ERROR->name)
                        <h2 class="font-bold text-xl">{{ session('header') ?? 'Oops!' }}</h2>
                        @break
                    @case(FlashMessageTypes::NOTIFICATION->name)
                        <h2 class="font-bold text-xl">{{ session('header') ?? 'Notice!' }}</h2>
                        @break
                    @default
                        <h2 class="font-bold text-xl">{{ session('header') ?? 'Flash!' }}</h2>
                @endswitch
                <p class="sm:inline-block block font-medium">{{ session('message') }}</p>
            </div>
        </div>
    </div>
@endif
