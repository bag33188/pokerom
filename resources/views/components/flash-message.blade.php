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
        {{--                 x-init="setTimeout(() => (show_flash_message = false), {{ session('timeout') ?? Js::from(5000) }})"
                        style="display: grid;grid-template-columns: 95% auto;grid-template-rows: auto;"
style="grid-column: 1/3;grid-row:1/-1;justify-self: center;width: 100%"
 style="grid-column: 2/3;justify-self: end;align-self:start;grid-row:1/-1;z-index: 10"
        --}}
        <div class="mx-3.5 my-3">
            <div
                @class(array_merge($flashMessageStyles,['grid-cols-[95%_auto]', 'grid-rows-[auto]', 'grid']))
                role="alert" type="{{ strtolower(Session::get('message-type')) ?? 'default' }}">
                <div
                    class="col-start-2 col-end-3 justify-self-end self-start row-span-full z-10">
                    <button type="button"
                            @click="show_flash_message = false"
                            class="text-red-500 text-2xl"
                    >
                        &times;
                    </button>
                </div>

                <div class="col-start-1 col-end-3 row-span-full justify-self-center w-full">
                    @switch(strtoupper(Session::get('message-type')))
                        @case(FlashMessageTypes::SUCCESS->name)
                            <h2 class="font-bold text-center text-xl w-full">{{ session('header') ?? 'Success!' }}</h2>
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
                    <p class="text-center w-full">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    </div>
@endif
