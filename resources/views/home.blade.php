@push('styles')
    <style {!! 'type="text/css"' !!}>
        .no-select {
            pointer-events: none !important;
            -webkit-touch-callout: none !important;
            -webkit-user-select: none !important;
            -khtml-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }
    </style>
@endpush
@prepend('scripts')
    <script type="text/javascript">
        const loadCopyrightYear = () => {
            const currentYear = (new Date()).getFullYear();
            const copyrightYearElement = document.getElementById("copyright-year");
            copyrightYearElement.textContent = currentYear.toString();
        };

        const loadEmulatorLinks = () => {
            const emulatorLinksList = document.getElementById("emulator-links");

            const anchorClasses = ["underline", "text-blue-400", "hover:text-blue-500"];

            @verbatim
            /** @type {Array<EmulatorObject>} */
            let emulators = [
                {
                    href: "https://desmume.org/",
                    text: "DeSmuME",
                    name: "desmume",
                    target: "_blank",
                    platform: "nintendo ds/lite (nds)"
                },
                {
                    href: "https://www.emulator-zone.com/doc.php/gba/vboyadvance.html",
                    text: "Visual Boy Advanced",
                    name: "vba",
                    target: "_blank",
                    title: "emulator-zone download page",
                    platform: "gameboy/gameboy color/gameboy advanced (gb/gbc/gba)"
                    // https://github.com/visualboyadvance-m/visualboyadvance-m
                },
                {
                    href: "https://citra-emu.org/",
                    text: "Citra",
                    name: "citra",
                    target: "_blank",
                    platform: "[new] nintendo 3ds/xl (3ds)"
                },
                {
                    href: "https://yuzu-emu.org/",
                    text: "Yuzu",
                    name: "yuzu",
                    target: "_blank",
                    platform: "nintendo switch/lite (nx)"
                }
            ];
            @endverbatim

            emulatorLinksList.style.setProperty("list-style-type", "none", "important");

            emulators.forEach((emulator, index) => {
                let listItemElement = document.createElement("li");
                let emulatorLinkElement = document.createElement("a");
                listItemElement.id = `emulator-${index + 1}`;

                emulatorLinkElement.id = `${emulator.name}-emulator-url`;
                emulatorLinkElement.href = emulator.href;
                emulatorLinkElement.text = emulator.text;
                emulatorLinkElement.target = emulator.target;
                emulatorLinkElement.rel = "noreferrer";
                emulatorLinkElement.setAttribute("data-platform", emulator.platform);

                if (emulators[index].hasOwnProperty("title"))
                    emulatorLinkElement.title = emulator["title"];

                emulatorLinkElement.classList.add(...anchorClasses);
                listItemElement.appendChild(emulatorLinkElement);
                emulatorLinksList.appendChild(listItemElement);
            });
        };

        function getCommentNodes(containerNode) {
            const treeWalker = document.createTreeWalker(containerNode, NodeFilter.SHOW_COMMENT);
            let comments = [];
            while (treeWalker.nextNode()) {
                comments.push(treeWalker.currentNode);
            }
            return comments;
        }

        const removeCommentsFromParentNode = (parentNodeSelector) => {
            const parentNode = document.querySelector(parentNodeSelector);
            let commentNodes = getCommentNodes(parentNode);
            commentNodes.forEach((commentNode) => {
                commentNode.remove();
            });
        };
    </script>
@endprepend
@push('scripts')
    <script type="text/javascript">
        removeCommentsFromParentNode('#emulator-links');
        removeCommentsFromParentNode('#copyright-year');
        loadCopyrightYear();
        loadEmulatorLinks();
    </script>
@endpush
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <div class="flex flex-row w-full justify-between">
                <span class="order-0">{{ __('Home') }}</span>
                <span class="order-1">Welcome, {{ $userFirstName }}!</span>
            </div>
        </h2>
    </x-slot>

    <div data-name="dashboard-container" @class([
        'px-1.5',    'mx-1.5',
        'py-1.5',    'my-1.5',
        'sm:my-2',   'sm:py-2',
        'md:my-3',   'md:py-3',
        'lg:my-4',   'lg:py-4',
        'xl:py-6',   'xl:my-6',
        '2xl:py-16', '2xl:my-16'
    ])>
        <div data-name="dash-content-card" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <section
                    data-name="heading"
                    class="p-4 sm:px-20 md:p-6 bg-white border-b-2 border-gray-200 flex flex-col md:flex-row justify-start md:justify-between items-center">
                    <div class="mb-3 md:mb-auto">
                        <x-jet-application-logo class="!h-12 !w-auto no-select"/>
                    </div>
                    <p class="inline-flex flex-row space-x-1.5 text-lg sm:text-xl md:text-2xl">
                        <span class="font-semibold">{!! config('app.web_name') !!}</span>
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
                                    ROMs, including all {{ GameQueries::getCountOfAllCoreGames() }} of the core Pok&#233;mon
                                    ROMs.
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
                                    Feel free to download them for use with an <u>emulator</u>.
                                </p>
                            </div>
                        </div>
                        <div class="mt-3.5 ml-11 inline-flex flex-row h-full items-end">
                            <x-anchor-button :btn-type="App\Enums\AnchorButtonTypeEnum::CAUTION"
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
                            <x-anchor-button :btn-type="App\Enums\AnchorButtonTypeEnum::CAUTION"
                                             href="{{ route('games.index') }}" target="_self">
                                <span class="order-1">Games</span>
                                <span class="order-2">@include('partials._more-info-icon')</span>
                            </x-anchor-button>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
