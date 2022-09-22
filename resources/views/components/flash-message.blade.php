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
    $successCssClasses = ['w-full', 'bg-green-100', 'border', 'border-green-400', 'text-green-700', 'px-3', 'py-2', 'rounded', 'relative'];
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
    >
        <div class="mx-3.5 my-3">
            <div
                @class(array_merge($flashMessageStyles, ['grid', 'grid-cols-[95%_auto]', 'grid-rows-[auto]', 'gap-0']))
                role="alert" type="{{ strtolower(Session::get('message-type')) ?? 'default' }}">
                <div
                    class="col-start-2 col-end-3 row-span-full justify-self-end self-start h-full inline-flex flex-row order-1">
                    <button type="button"
                            @click="show_flash_message = false"
                            class="text-red-500 text-3xl self-stretch font-black">
                        &times;
                    </button>
                </div>
                <div class="col-start-1 col-end-3 row-span-full justify-self-center w-full order-0">
                    <div class="w-full h-full inline-flex flex-col justify-center">
                        @switch(strtoupper(Session::get('message-type')))
                            @case(FlashMessageTypes::SUCCESS->name)
                                <h2 class="font-bold text-xl text-center">{{ session('header') ?? 'Success!' }}</h2>
                                @break
                            @case(FlashMessageTypes::ERROR->name)
                                <h2 class="font-bold text-xl text-center">{{ session('header') ?? 'Oops!' }}</h2>
                                @break
                            @case(FlashMessageTypes::NOTIFICATION->name)
                                <h2 class="font-bold text-xl text-center">{{ session('header') ?? 'Notice!' }}</h2>
                                @break
                            @default
                                <h2 class="font-bold text-xl text-center">{{ session('header') ?? 'Flash!' }}</h2>
                        @endswitch
                        <p class="font-medium text-center w-full">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
