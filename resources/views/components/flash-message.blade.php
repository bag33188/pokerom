@pushOnce('scripts')
    <script type="text/javascript">
        @verbatim
        /**
         * @name flashMessageData
         * @returns {FlashMessageObj}
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
    $successFlashMessageStyles = 'class="w-full text-center bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"';
    $errorFlashMessageStyles = 'class="w-full text-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"';
    $defaultFlashMessageStyles = 'class="w-full text-center bg-blue-100 border border-blue-400 text-blue-600 rounded relative"';
@endphp
@if(Session::has('message'))
    <div {{ $attributes }}
         x-data="flashMessageData()"
         x-init="setTimeout(() => (show_flash_message = false), {{ session()->has('timeout') ? session('timeout') : Js::from(5000) }})"
         x-show="flashMessageIsVisible(show_flash_message)"
    >
        @switch(strtolower(Session::get('message-type')))
            @case('success')
                <div class="mx-3.5 my-3">
                    <div
                        {!! $successFlashMessageStyles !!}
                        role="alert" type="success">
                        <button type="button"
                                @click="show_flash_message = false"
                                class="mr-2.5 mt-1 text-red-500 float-right clear-none text-2xl">
                            &times;
                        </button>
                        <h2 class="font-bold text-xl">Success!</h2>
                        <p class="sm:inline-block block font-medium">{{ session('message') }}</p>
                    </div>
                </div>
                @break
            @case('error')
                <div class="mx-3.5 my-3">
                    <div
                        {!! $errorFlashMessageStyles !!}
                        role="alert" type="error">
                        <button type="button"
                                @click="show_flash_message = false"
                                class="mr-2.5 mt-1 text-red-500 float-right clear-none text-2xl">
                            &times;
                        </button>
                        <h2 class="font-bold text-xl">Error!</h2>
                        <p class="sm:inline-block block font-medium">{{ session('message') }}</p>
                    </div>
                </div>
                @break
            @default
                <div class="mx-3.5 my-3">
                    <div
                        {!! $defaultFlashMessageStyles !!}
                        role="alert" type="info">
                        <button type="button"
                                @click="show_flash_message = false"
                                class="mr-2.5 mt-1 text-red-500 float-right clear-none text-2xl">
                            <b>&times;</b>
                        </button>
                        <div class="px-4 py-3">
                            <h2 class="font-bold text-xl">Notice!</h2>
                            <p class="sm:inline-block block font-medium">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
        @endswitch
    </div>
@endif
