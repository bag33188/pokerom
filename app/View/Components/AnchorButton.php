<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnchorButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public string $type = 'primary')
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        $getEnumValuesAsArray = fn(array $enumCases): array => array_column($enumCases, 'value');
        return view('components.anchor-button', [
            'resolveAnchorBtnType' => function (string &$btnType, string $defaultTypeValue, array $btnTypeEnumCases) use ($getEnumValuesAsArray): void {
                if (empty($btnType)) {
                    $btnType = strtolower($defaultTypeValue);
                } else if (!in_array($btnType, $getEnumValuesAsArray($btnTypeEnumCases))) {
                    $btnType = strtolower($defaultTypeValue);
                } else {
                    $btnType = strtolower($btnType);
                }
            }
        ]);
    }
}
