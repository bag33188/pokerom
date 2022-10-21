<?php

if (!function_exists('strip_quotes')) {
    function strip_quotes(string $value): string
    {
        $quoteRegExp = "/([\x{22}\x{27}])|(\x{26}(?:quot|apos|\x{23}0?3[94])\x{3B})/iu";
        return (string)preg_replace($quoteRegExp, '', $value);
    }
}
