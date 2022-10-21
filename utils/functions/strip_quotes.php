<?php

if (!function_exists('strip_quotes')) {
    function strip_quotes(string $value): string
    {
        return (string)preg_replace(QUOTE_PATTERN, '', $value);
    }
}
