<?php

if (!function_exists('str_capitalize')) {
    /**
     * String Capitalize
     *
     * This function is a more advanced version of the {@see ucfirst} function included with PHP
     *
     * This function gives you the following options:
     *  + capitalize all words in a string with multiple words
     *  + choose how many words to capitalize in a string with multiple words
     *  + define the delimiter to separate the words you wish to capitalize
     *
     * @param string $value String to capitalize
     * @param bool $deep Set to true if you have a string with more than 1 word, and you want to capitalize ALL the words in that string.
     * @param int $depth Set to any value greater than 0 if you wish to only capitalize a string of words up until a certain word
     * @param string $separator Set to any string value if you wish to distinguish the words in your string **by a character other than space**
     * @return string|null
     */
    function str_capitalize(string $value, bool $deep = false, string $separator = _SPACE, int $depth = 0): ?string
    {
        $value = trim($value);
        if (!strlen($value) || !$value) return null;
        $str_arr = explode($separator, $value);
        $word_count = count($str_arr);
        if ($deep === true && $depth > 0) {
            if ($depth > $word_count) $depth = $word_count;
            $i = 0;
            do {
                $str_arr[$i] = sprintf("%s%s",
                    strtoupper($str_arr[$i][0]),
                    strtolower(substr($str_arr[$i], 1, strlen($str_arr[$i]) - 1)));
                $i++;
            } while ($i < $depth);
            return implode($separator, $str_arr);
        } else if ($deep === true && $depth === 0) {
            for ($i = 0; $i < $word_count; $i++) {
                $str_arr[$i] = sprintf("%s%s",
                    strtoupper($str_arr[$i][0]),
                    strtolower(substr($str_arr[$i], 1, strlen($str_arr[$i]) - 1)));
            }
            return join($separator, $str_arr);
        } else {
            $conversions = (object)array(
                'php' => strtoupper($value[0]) . strtolower(substr($value, 1, strlen($value) - 1)),
                'c' => ucfirst($value) // <- native
            );
            return $conversions->c;
        }
    }
}
