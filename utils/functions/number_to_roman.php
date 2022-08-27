<?php

if (!function_exists('numberToRoman')) {
    /**
     * ## Number To Roman
     *
     * Converts a given integer into a roman numeral
     *
     * **Notes**:
     *
     * Only use with numbers less than or equal to _`2000`_
     *
     * Returns _`N/A`_ if number was _`0`_
     *
     * @param int $number
     * @return string
     */
    function numberToRoman(int $number): string
    {
        if ($number === 0) {
            return "N/A";
        }
        $map = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}
