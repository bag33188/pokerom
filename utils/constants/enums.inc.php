<?php

/*
 * Database Enum Values
 */

/** array of valid game types */
const GAME_TYPES = array('core', 'spin-off', 'hack');
/** array of valid game regions */
const REGIONS = array(
    'kanto',
    'johto',
    'hoenn',
    'sinnoh',
    'unova',
    'kalos',
    'alola',
    'galar',
    'other'
);
/** array of valid rom types */
const ROM_TYPES = array('gb', 'gbc', 'gba', 'nds', '3ds', 'xci');
/** array of valid user roles */
const USER_ROLES = array('guest', 'user', 'admin');
/** array of valid rom file types */
const ROMFILE_TYPES = array('.GB', '.GBC', '.GBA', '.NDS', '.3DS', '.XCI');
