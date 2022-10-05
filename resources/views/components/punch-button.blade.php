@pushOnce('styles')
    @env('local')
        <!--suppress CssUnresolvedCustomProperty -->
    @endenv
    <style {!! 'type="text/css"' !!}>
        :root {
            --borderline-black: #111111;
            --borderline-agean: #1f3053;
            --borderline-sky-blue: #5c8bee;
            --borderline-denim: #1d2c4d;
            --borderline-cerulean: #87adff;
        }
    </style>
    <style {!! 'type="text/css"' !!}>
        a.punch,
        button.punch {
            --deep-navy: #1f2d4d;
            display: inline-block !important;
            background: #4162a8 !important;
            border-top: 1px solid #38538c !important;
            border-right: 1px solid var(--deep-navy) !important;
            border-bottom: 1px solid #151e33 !important;
            border-left: 1px solid var(--deep-navy) !important;
            -webkit-border-radius: 4px !important;
            -moz-border-radius: 4px !important;
            -ms-border-radius: 4px !important;
            -o-border-radius: 4px !important;
            border-radius: 4px !important;
            -webkit-box-shadow: inset 0 1px 10px 1px var(--borderline-sky-blue),
            0 1px 0 var(--borderline-denim), 0 6px 0 var(--borderline-agean),
            0 8px 4px 1px var(--borderline-black) !important;
            -moz-box-shadow: inset 0 1px 10px 1px var(--borderline-sky-blue),
            0 1px 0 var(--borderline-denim), 0 6px 0 var(--borderline-agean),
            0 8px 4px 1px var(--borderline-black) !important;
            -ms-box-shadow: inset 0 1px 10px 1px var(--borderline-sky-blue),
            0 1px 0 var(--borderline-denim), 0 6px 0 var(--borderline-agean),
            0 8px 4px 1px var(--borderline-black) !important;
            -o-box-shadow: inset 0 1px 10px 1px var(--borderline-sky-blue),
            0 1px 0 var(--borderline-denim), 0 6px 0 var(--borderline-agean),
            0 8px 4px 1px var(--borderline-black) !important;
            box-shadow: inset 0 1px 10px 1px var(--borderline-sky-blue),
            0 1px 0 var(--borderline-denim), 0 6px 0 var(--borderline-agean),
            0 8px 4px 1px var(--borderline-black) !important;
            color: #fff !important;
            font: bold 14px "helvetica neue", helvetica, arial, sans-serif !important;
            line-height: 1 !important;
            margin-bottom: 15px !important;
            padding: 8px 0 10px 0 !important;
            text-align: center !important;
            text-shadow: 0 -1px 1px #1e2d4d !important;
            text-decoration: none !important;
            width: 225px !important;
            -webkit-background-clip: padding-box !important;
        }

        a.punch:hover:not([disabled]),
        button.punch:hover:not([disabled]) {
            -webkit-box-shadow: inset 0 0 20px 1px var(--borderline-cerulean),
            0 1px 0 var(--borderline-denim), 0 6px 0 var(--borderline-agean),
            0 8px 4px 1px var(--borderline-black) !important;
            -moz-box-shadow: inset 0 0 20px 1px var(--borderline-cerulean),
            0 1px 0 var(--borderline-denim), 0 6px 0 var(--borderline-agean),
            0 8px 4px 1px var(--borderline-black) !important;
            -ms-box-shadow: inset 0 0 20px 1px var(--borderline-cerulean),
            0 1px 0 var(--borderline-denim), 0 6px 0 var(--borderline-agean),
            0 8px 4px 1px var(--borderline-black) !important;
            -o-box-shadow: inset 0 0 20px 1px var(--borderline-cerulean),
            0 1px 0 var(--borderline-denim), 0 6px 0 var(--borderline-agean),
            0 8px 4px 1px var(--borderline-black) !important;
            box-shadow: inset 0 0 20px 1px var(--borderline-cerulean),
            0 1px 0 var(--borderline-denim), 0 6px 0 var(--borderline-agean),
            0 8px 4px 1px var(--borderline-black) !important;
            cursor: pointer !important;
        }

        a.punch:active:not([disabled]),
        button.punch:active:not([disabled]) {
            -webkit-box-shadow: inset 0 1px 10px 1px var(--borderline-sky-blue),
            0 1px 0 var(--borderline-denim), 0 2px 0 var(--borderline-agean),
            0 4px 3px 0 var(--borderline-black) !important;
            -moz-box-shadow: inset 0 1px 10px 1px var(--borderline-sky-blue),
            0 1px 0 var(--borderline-denim), 0 2px 0 var(--borderline-agean),
            0 4px 3px 0 var(--borderline-black) !important;
            -ms-box-shadow: inset 0 1px 10px 1px var(--borderline-sky-blue),
            0 1px 0 var(--borderline-denim), 0 2px 0 var(--borderline-agean),
            0 4px 3px 0 var(--borderline-black) !important;
            -o-box-shadow: inset 0 1px 10px 1px var(--borderline-sky-blue),
            0 1px 0 var(--borderline-denim), 0 2px 0 var(--borderline-agean),
            0 4px 3px 0 var(--borderline-black) !important;
            box-shadow: inset 0 1px 10px 1px var(--borderline-sky-blue),
            0 1px 0 var(--borderline-denim), 0 2px 0 var(--borderline-agean),
            0 4px 3px 0 var(--borderline-black) !important;
            margin-top: 5px !important;
            margin-bottom: 10px !important;
        }

        a.punch:disabled,
        button.punch:disabled,
        a.punch.disabled {
            background: gray !important;
            cursor: not-allowed !important;
            -webkit-box-shadow: inset 0 1px 10px 1px lightgrey, 0 1px 0 darkgrey,
            0 2px 0 darkgray, 0 4px 3px 0 black !important;
            -moz-box-shadow: inset 0 1px 10px 1px lightgrey, 0 1px 0 darkgrey,
            0 2px 0 darkgray, 0 4px 3px 0 black !important;
            -ms-box-shadow: inset 0 1px 10px 1px lightgrey, 0 1px 0 darkgrey,
            0 2px 0 darkgray, 0 4px 3px 0 black !important;
            -o-box-shadow: inset 0 1px 10px 1px lightgrey, 0 1px 0 darkgrey,
            0 2px 0 darkgray, 0 4px 3px 0 black !important;
            box-shadow: inset 0 1px 10px 1px lightgrey, 0 1px 0 darkgrey,
            0 2px 0 darkgray, 0 4px 3px 0 black !important;
            border: 1px solid black !important;
        }

        a.punch.disabled {
            pointer-events: none !important;
            -webkit-touch-callout: none !important;
            -webkit-user-select: none !important;
            -khtml-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }
    </style>
@endPushOnce
@switch($btnType)
    @case('button')
    @case('submit')
        <button
            element-type="button"
            {!! $attributes->class(['punch'])->merge(['type' => $btnType]) !!}
            {{ $attributes->has('disabled') ? 'disabled' : '' }}
            @disabled(!$userIsAdmin)
        >
            {{ $slot }}
        </button>
        @break
    @case('anchor')
        @prependOnce('scripts')
            @verbatim
                <script type="text/javascript">
                    /**
                     * @name disablePunchButton
                     * @description Invoke this function to disable the punch button if you are using it as an {@link HTMLAnchorElement anchor tag}.
                     * @summary Disables the button, nullifies the hyperlink, and removes any pointer events
                     * @param {string} buttonID ID of the punch button {@link HTMLAnchorElement anchor tag} to disable
                     * @returns {boolean} Returns true if the button was disabled, false if the button was not disabled
                     */
                    let disablePunchButton = function (buttonID) {
                        const button = document.getElementById(buttonID);
                        if (button.getAttribute('data-type').toLowerCase() === 'anchor') {
                            button.setAttribute('class', 'punch disabled');
                            button.setAttribute('href', 'javascript:void(0)');
                            button.setAttribute('target', '_blank');
                            return true;
                        } else {
                            return false;
                        }
                    };

                    window.disablePunchButton = disablePunchButton;
                </script>
            @endverbatim
        @endPrependOnce
        <a element-type="anchor"
            {!! $attributes->class(['punch', 'disabled' => !$userIsAdmin])->merge(['role' => $btnType]) !!}>
            {{ $slot }}
        </a>
        @break
    @default
        @break
@endswitch
