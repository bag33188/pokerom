@php
    use App\Enums\FlashMessageTypeEnum as FlashMessageTypes;

    $successCssClasses = ['w-full', 'bg-green-100', 'border', 'border-green-400', 'text-green-700', 'px-3', 'py-2', 'rounded', 'relative'];
    $warnCssClasses = ['w-full', 'bg-yellow-300', 'border', 'border-yellow-500', 'text-yellow-800', 'px-3', 'py-2', 'rounded', 'relative'];
    $errorCssClasses = ['w-full', 'text-center', 'bg-red-100', 'border', 'border-red-400', 'text-red-700', 'px-3', 'py-2', 'rounded', 'relative'];
    $notificationCssClasses = ['w-full', 'text-center', 'border', 'bg-blue-100', 'border-blue-400', 'text-blue-600', 'px-3', 'py-2', 'rounded', 'relative'];
    $defaultCssClasses = ['w-full', 'text-center', 'bg-gray-200', 'border', 'border-gray-400', 'text-gray-600', 'px-3', 'py-2', 'rounded', 'relative'];

    $flashMessageStyles = array();

    match ($messageType) {
        FlashMessageTypes::SUCCESS => $flashMessageStyles = $successCssClasses,
        FlashMessageTypes::ERROR => $flashMessageStyles = $errorCssClasses,
        FlashMessageTypes::WARNING => $flashMessageStyles = $warnCssClasses,
        FlashMessageTypes::NOTIFICATION => $flashMessageStyles = $notificationCssClasses,
        default => $flashMessageStyles = $defaultCssClasses,
    };
@endphp
@pushOnce('scripts')
    @verbatim
        <script type="text/javascript">
            /**
             * @name flashMessageData
             * @returns {FlashMessageObject}
             */
            function flashMessageData() {
                return {
                    show_flash_message: true
                };
            }

            /**
             * @name flashMessageIsVisible
             * @param {boolean} show_flash_message
             * @returns {boolean}
             */
            const flashMessageIsVisible = (show_flash_message) => show_flash_message === true;
        </script>
    @endverbatim
@endPushOnce
@if($sessionHasMessage)
    <div {{ $attributes }}
         x-data="flashMessageData()"
         x-show="flashMessageIsVisible(show_flash_message)"
         x-init="setTimeout(() => (show_flash_message = false), {{ Js::from($sessionFlashTimeout) }})"
    >
        <div class="mx-3.5 my-3">
            <div
                @class(array_merge($flashMessageStyles, ['grid', 'grid-cols-[95%_auto]', 'grid-rows-[auto]', 'gap-0']))
                role="alert" type="{{ strtolower($messageType->name) }}">
                <div @class([
                    'col-start-2',
                    'col-end-3',
                    'row-span-full',
                    'justify-self-end',
                    'h-full',
                    'inline-flex',
                    'flex-row',
                    'items-start',
                    'order-1',
                    '-mt-1.5'
                ])>
                    <button type="button"
                            @click="show_flash_message = false"
                            class="text-red-500 text-3xl font-black">
                        {!! "&times;" !!}
                    </button>
                </div>
                <div class="col-start-1 col-end-3 row-span-full justify-self-center w-full order-0">
                    <div class="w-full h-full inline-flex flex-col justify-center">
                        @switch($messageType)
                            @case(FlashMessageTypes::SUCCESS)
                                <h2 class="font-bold text-xl text-center"
                                    data-flash-type="{{ FlashMessageTypes::SUCCESS->value }}">
                                    {{ session('header', 'Success!') }}
                                </h2>
                                @break
                            @case(FlashMessageTypes::NOTIFICATION)
                                <h2 class="font-bold text-xl text-center"
                                    data-flash-type="{{ FlashMessageTypes::NOTIFICATION->value }}">
                                    {{ session('header', 'Notice!') }}
                                </h2>
                                @break
                            @case(FlashMessageTypes::ERROR)
                                <h2 class="font-bold text-xl text-center"
                                    data-flash-type="{{ FlashMessageTypes::ERROR->value }}">
                                    {{ session('header', 'Oops!') }}
                                </h2>
                                @break
                            @case(FlashMessageTypes::WARNING)
                                <h2 class="font-bold text-xl text-center"
                                    data-flash-type="{{ FlashMessageTypes::WARNING->value }}">
                                    {{ session('header', 'Warning!') }}
                                </h2>
                                @break
                            @default
                                <h2 class="font-bold text-xl text-center"
                                    data-flash-type="{{ FlashMessageTypes::DEFAULT->value }}">
                                    {{ session('header', 'Flash!') }}
                                </h2>
                        @endswitch
                        <p class="font-medium text-center w-full">{{ session('message') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
