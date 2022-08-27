<?php

/*
 * Validation Pattern Regular Expressions
 */

/** rom filename regular expression */
const ROM_FILENAME_PATTERN = /** @lang RegExp */
"/^([\w\d\-_]{1,28})\.(3ds|xci|nds|gbc|gb|gba)$/i";
/** game name regular expression */
const GAME_NAME_PATTERN = /** @lang RegExp */
"/^Pokemon\s.+$/";
/** rom name regular expression */
const ROM_NAME_PATTERN = /** @lang RegExp */
"/^[\w\d_\-]+$/i";
