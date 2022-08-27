<?php

if (!function_exists('joinCssClasses')) {
    /**
     * ## Join CSS Classes Into HTML String
     *
     * Convert an array of CSS classes and join them together into an HTML class-attribute string.
     *
     * @param string[] $classes string array of CSS classes
     * @return string joined HTML class string
     */
    function joinCssClasses(array $classes): string
    {
        return implode(_SPACE, $classes);
    }
}
