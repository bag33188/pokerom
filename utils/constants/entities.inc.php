<?php

/*
 * Application Entity Values
 */

/** the special _**&eacute;**_ character in the Pokemon logo */
const _EACUTE = "\u{00E9}";
/** **&Eacute;** , uppercase version of {@see _EACUTE} */
const U_EACUTE = "\u{00C9}";
/** Unicode's entity for a space char */
const _SPACE = "\u{0020}";


/** directory that contains all bin rom files */
const ROM_FILES_DIRNAME = 'rom_files';
/** the key name for sanctum personal access token */
const API_TOKEN_KEY = 'auth_token';
/** concat: Pok, &eacute; ... output: Pok&eacute; */
const POKE_EACUTE = "Pok" . _EACUTE;


/** Pokemon Green release date */
const FIRST_POKEMON_GAME_RELEASE_DATE = '1996-02-27';
