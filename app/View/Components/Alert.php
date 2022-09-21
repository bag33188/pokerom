<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $type = 'default')
    {
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        return view('components.alert', [
            'resolveAlertType' => function (string &$alertType, string $defaultValue, array $alertTypeEnumValues): void {
                if (empty($alertType)) {
                    $alertType = strtolower($defaultValue);
                } else if (!in_array($alertType, $alertTypeEnumValues)) {
                    $alertType = strtolower($defaultValue);
                } else {
                    $alertType = strtolower($alertType);
                }
            }
        ]);
    }
}
