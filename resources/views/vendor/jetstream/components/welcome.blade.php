@prepend('scripts')
    <script type="text/javascript">
        const loadCopyrightYear = () => {
            const currentYear = (new Date()).getFullYear();
            const copyrightYearElement = document.getElementById('copyright-year');
            copyrightYearElement.textContent = currentYear.toString();
        };

        const loadEmulatorLinks = () => {
            /** @type HTMLUListElement */
            const emulatorLinksList = document.getElementById("emulator-links");

            /**
             * these classes are under the `saved`/`safe` array/list in tailwind config due to
             * classes **not being able to be generated** at build time when hardcoded in js
             *
             * @constant
             * @name anchorClasses
             * @type {string[]}
             */
            const anchorClasses = ["underline", "text-blue-400", "hover:text-blue-500"];

            /**
             * @name emulators
             * @type {Array<EmulatorObject>}
             * @description Array of nintendo emulator data for use with making an _unordered_ {@link HTMLLIElement List} of {@link HTMLAnchorElement HTML Anchor Elements}
             */
            let emulators = [
                {
                    href: "https://desmume.org/",
                    text: "DeSmuME",
                    name: "desmume",
                    target: "_blank",
                    platform: 'nintendo ds/lite (nds)'
                },
                {
                    href: "https://www.emulator-zone.com/doc.php/gba/vboyadvance.html",
                    text: "Visual Boy Advanced",
                    name: "vba",
                    target: "_blank",
                    title: "emulator-zone download page",
                    platform: 'gameboy/gameboy color/gameboy advanced (gb/gbc/gba)'
                    // https://github.com/visualboyadvance-m/visualboyadvance-m
                },
                {
                    href: "https://citra-emu.org/",
                    text: "Citra",
                    name: "citra",
                    target: "_blank",
                    platform: '[new] nintendo 3ds/xl (3ds)'
                },
                {
                    href: "https://yuzu-emu.org/",
                    text: "Yuzu",
                    name: "yuzu",
                    target: "_blank",
                    platform: 'nintendo switch/lite (nx)'
                }
            ];

            emulatorLinksList.style.setProperty('list-style-type', 'none', 'important');

            emulators.forEach((emulator, index) => {
                let listItemElement = document.createElement("li");
                let emulatorLinkElement = document.createElement("a");
                listItemElement.id = `emulator-${(index + 1).valueOf().toString()}`;

                emulatorLinkElement.id = `${emulator.name}-emulator-url`;
                emulatorLinkElement.href = emulator.href;
                emulatorLinkElement.text = emulator.text;
                emulatorLinkElement.target = emulator.target;
                emulatorLinkElement.rel = "noreferrer";
                emulatorLinkElement.setAttribute('data-platform', emulator.platform);

                if (emulators[index].hasOwnProperty('title'))
                    emulatorLinkElement.title = emulator['title'];

                emulatorLinkElement.classList.add(...anchorClasses);
                listItemElement.appendChild(emulatorLinkElement);
                emulatorLinksList.appendChild(listItemElement);
            });
        };
    </script>
@endprepend
@push('scripts')
    <script type="text/javascript">
        loadCopyrightYear();
        loadEmulatorLinks();
    </script>
@endpush
<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
    <section
        data-name="heading"
        class="p-4 sm:px-20 md:p-6 bg-white border-b-2 border-gray-200 flex flex-col md:flex-row justify-start md:justify-between items-center">
        <div class="mb-3 md:mb-auto">
            <x-jet-application-logo class="!h-12 !w-auto"/>
        </div>
        <p class="text-lg sm:text-xl md:text-2xl">
                        <span class="font-semibold">
                            {!! config('app.web_name') !!}
                        </span>
            <span class="font-bold">&#160;&#8209;&#xA0;</span>
            <span class="italic">One Stop for all your Pok&eacute;mon ROMs</span>
        </p>
    </section>
    <div
        class="bg-gray-200 bg-opacity-25 grid grid-rows-[repeat(3,auto)] grid-cols-1 md:grid-cols-2 md:grid-rows-[auto_auto]">
        <section data-name="about" class="p-6 border-b border-gray-200 col-span-2 row-span-1">
            <h3 class="ml-12 text-lg text-gray-600 leading-7 font-semibold">About</h3>
            <div class="ml-12">
                <div class="mt-2 text-md text-gray-500">
                    <!-- about description -->
                    <p class="inline-block">
                        This web app is a databank of Pok&#xE9;mon ROMs.
                        <wbr/>
                        This databank contains
                        <span id="adv-count"><!--more than-->{{ $romsDisplayCount }}+</span>
                        ROMs, including all 33 of the core Pok&#233;mon ROMs.
                    </p>
                    <br/>
                    <p class="italic mt-3 mb-0 pb-0 inline-flex flex-row text-sm">
                        <span>&copy; Pok&eacute;mon Company</span>
                        <span>&nbsp;</span>
                        <span id="copyright-year"><!-- js content insert --></span>
                    </p>
                </div>
            </div>
        </section>
        <section
            data-name="roms"
            class="flex flex-col p-6 border-r border-t border-gray-200 row-start-2 row-end-2 col-span-full md:col-start-1 md:col-end-1 h-full">
            <h3 class="ml-12 text-lg text-gray-600 leading-7 font-semibold">Roms</h3>
            <div class="ml-12">
                <div class="mt-2 text-sm text-gray-500">
                    <!-- roms description -->
                    <p class="inline-block">
                        Here you will find all your <strong>Core Pok&#xE9;mon ROMs</strong>,
                        <wbr/>
                        as well as some <b>Spin-Off Games</b>,
                        <wbr/>
                        and even some <b>Pok&#233;mon ROM hacks</b>.
                        <wbr/>
                        <br/>
                        <br/>
                        Feel free to download them for use with an <i>emulator</i>.
                    </p>
                </div>
            </div>
            <div class="mt-3.5 ml-11 inline-flex flex-row h-full items-end">
                <x-anchor-button :btn-type="\App\Enums\AnchorButtonTypeEnum::INFO"
                                 href="{{ route('roms.index') }}" target="_self">
                    <span class="order-1">ROMs</span>
                    <span class="order-2">@include('partials._more-info-icon')</span>
                </x-anchor-button>
            </div>
        </section>
        <section
            data-name="games"
            class="flex flex-col p-6 border-l border-t border-gray-200 md:row-start-2 md:row-end-2 row-start-3 row-end-3 col-span-full md:col-start-2 md:col-end-2 h-full">
            <h3 class="ml-12 text-lg text-gray-600 leading-7 font-semibold">Games</h3>
            <div class="ml-12">
                <div class="mt-2 text-sm text-gray-500">
                    <!-- games description -->
                    <p class="inline-block">Feel free to play these amazing Games on your emulators!!</p>
                    <ul id="emulator-links" class="mt-1.5">
                        <!-- js content insert -->
                    </ul>
                </div>
            </div>
            <div class="mt-3 ml-11 inline-flex flex-row h-full items-end">
                <x-anchor-button :btn-type="\App\Enums\AnchorButtonTypeEnum::INFO"
                                 href="{{ route('games.index') }}" target="_self">
                    <span class="order-1">Games</span>
                    <span class="order-2">@include('partials._more-info-icon')</span>
                </x-anchor-button>
            </div>
        </section>
    </div>
</div>
