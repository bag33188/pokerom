<?php

if (!defined('FSI_FLAGS')) {
    define('FSI_FLAGS', FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::SKIP_DOTS);
}

if (!function_exists('dir_is_empty')) {
    /**
     * ## Directory Is Empty (Boolean)
     *
     * Checks if a given directory is empty (devoid of any and all files)
     *
     * _Loaded in `composer.json`_
     *
     * ----
     *
     * @link https://github.com/bag33188/pokerom_rebirth/blob/main/composer.json
     * @param string $dir
     * @return bool
     */
    function dir_is_empty(string $dir): bool
    {
        $iterator = new FilesystemIterator($dir, FSI_FLAGS);
        return $iterator->valid() === false;
    }
}
