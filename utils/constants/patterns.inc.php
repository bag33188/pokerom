<?php

/*
 * Validation Pattern Regular Expressions
 */

/** rom filename regular expression */
const ROM_FILENAME_PATTERNS = array(
    /** @lang RegExp */
    "/^([\w\d\-_]{1,28})\.(3ds|xci|nds|gbc|gb|gba)$/i",
    /** @lang RegExp */
    "/^([\w\-_]+\/)([\w\d\-_]{1,28})\.(3ds|xci|nds|gbc|gb|gba)$/i"
);
/** game name regular expression */
const GAME_NAME_PATTERN = /** @lang RegExp */
"/^Pokemon\s.+$/i";
/** rom name regular expression */
const ROM_NAME_PATTERN = /** @lang RegExp */
"/^[\w\d_\-]+$/i";
/** regex for detecting {@see _EACUTE}, {@see U_EACUTE} */
const EACUTE_PATTERN = /** @lang RegExp */
"/[\x{E9}\x{C9}]/u";
