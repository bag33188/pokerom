<?php

/*
 * Validation Pattern Regular Expressions
 */

/** rom filename regular expression */
const ROM_FILENAME_PATTERN =
    /** @lang RegExp */
"/^([\w\d\-_]{1,28})\.(gb(?:[ac]?)|nds|3ds|xci)$/i";
/** game name regular expression */
const GAME_NAME_PATTERN = /** @lang RegExp */
"/^Pokemon\s.+$/i";
/** rom name regular expression */
const ROM_NAME_PATTERN = /** @lang RegExp */
"/^[\w\d_\-]+$/i";
/** regex for detecting {@see _EACUTE}, {@see U_EACUTE} */
const EACUTE_PATTERN = /** @lang RegExp */
"/[\x{E9}\x{C9}]/u";

const POKE_PATTERN = /** @lang RegExp */
"/^(poke)/i";
