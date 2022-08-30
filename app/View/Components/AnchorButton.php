<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnchorButton extends Component
{
    // todo: add enum for this
    public string $btnType;

    /**
     * Create a new component instance.
     *
     * @param string $btnType
     * @return void
     */
    public function __construct(string $btnType = 'primary')
    {
        $this->btnType = $btnType;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        $anchorPrimaryClasses = ['inline-flex', 'items-center', 'px-4', 'py-2', 'bg-gray-800', 'border', 'border-transparent', 'rounded-md', 'font-semibold', 'text-xs', 'text-white', 'uppercase', 'tracking-widest', 'hover:bg-gray-700', 'active:bg-gray-900', 'focus:outline-none', 'focus:border-gray-900', 'focus:ring', 'focus:ring-gray-300', 'disabled:opacity-25', 'transition'];
        $anchorSecondaryClasses = ['inline-flex', 'items-center', 'px-4', 'py-2', 'bg-white', 'border', 'border-gray-300', 'rounded-md', 'font-semibold', 'text-xs', 'text-gray-700', 'uppercase', 'tracking-widest', 'shadow-sm', 'hover:text-gray-500', 'focus:outline-none', 'focus:border-blue-300', 'focus:ring', 'focus:ring-blue-200', 'active:text-gray-800', 'active:bg-gray-50', 'disabled:opacity-25', 'transition'];
        $anchorDangerClasses = ['inline-flex', 'items-center', 'px-4', 'py-2', 'bg-gray-800', 'border', 'border-transparent', 'rounded-md', 'font-semibold', 'text-xs', 'text-white', 'uppercase', 'tracking-widest', 'hover:bg-gray-700', 'active:bg-gray-900', 'focus:outline-none', 'focus:border-gray-900', 'focus:ring', 'focus:ring-gray-300', 'disabled:opacity-25', 'transition'];
        return view('components.anchor-button', [
            'anchorPrimary' => $anchorPrimaryClasses,
            'anchorSecondary' => $anchorSecondaryClasses,
            'anchorDanger' => $anchorDangerClasses,
        ]);
    }
}
