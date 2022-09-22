@pushOnce('scripts')
    <script type="text/javascript">
        function flashMessageData() {
            return {
                show_flash_message: true
            }
        }

        const flashMessageIsVisible = (show_flash_message) => show_flash_message === true;
    </script>
@endPushOnce
@if(Session::has('message'))
    <div {{ $attributes }}
         x-data="flashMessageData()"
         x-init="setTimeout(() => (show_flash_message = false), {{ session()->has('timeout') ? session('timeout') : Js::from(5000) }})"
    >
        @switch(strtolower(session('message-type')))
            @case('success')
                <div class="w-full flex justify-center">
                    <div
                        class="w-full text-center bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert" type="success" x-show="flashMessageIsVisible(show_flash_message)">
                        <button type="button" @click="show_flash_message = false" class="text-red-500 float-right">X
                        </button>
                        <h2 class="font-bold text-xl">Success!</h2>
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                </div>
                @break
            @case('error')
                <div class="w-full flex justify-center">
                    <div
                        class="w-full text-center bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert" type="error" x-show="flashMessageIsVisible(show_flash_message)">
                        <button type="button" @click="show_flash_message = false" class="text-red-500 float-right">X
                        </button>
                        <h2 class="font-bold text-xl">Error!</h2>
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                </div>
                @break
            @default
                <div class="w-full flex justify-center">
                    <div
                        class="w-full text-center bg-blue-100 border border-blue-400 text-blue-600 px-4 py-3 rounded relative"
                        role="alert" type="info" x-show="flashMessageIsVisible(show_flash_message)">
                        <button type="button" @click="show_flash_message = false" class="text-red-500 float-right">X
                        </button>
                        <h2 class="font-bold text-xl">Notice!</h2>
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                </div>
        @endswitch
    </div>
@endif
