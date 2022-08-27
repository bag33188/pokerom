<?php

if (!function_exists('str_to_bool')) {
    /**
     * ## String To Boolean
     *
     * Convert string boolean value to boolean
     *
     * @param string|null $value value to cast. can be null if referenced passed-into function is null
     * @return bool
     */
    function str_to_bool(?string $value): bool
    {
        if (empty($value)) $value = 'false';
        return filter_var($value, FILTER_VALIDATE_BOOL);
    }
}
