<?php

/*
 * Validation Length Values
 */

// LENGTHS //

const MAX_GAME_NAME_LENGTH = 40;
const MIN_GAME_NAME_LENGTH = 7;
const MAX_GAME_TYPE_LENGTH = 8;
const MIN_GAME_TYPE_LENGTH = 4;
const MAX_GAME_REGION_LENGTH = 8;
const MIN_GAME_REGION_LENGTH = 4;


const MAX_ROM_NAME_LENGTH = 30;
const MIN_ROM_NAME_LENGTH = 3;
const MAX_ROM_TYPE_LENGTH = 4;
const MIN_ROM_TYPE_LENGTH = 2;


const MAX_USER_NAME_LENGTH = 45;
const MIN_USER_NAME_LENGTH = 1;
const MAX_USER_EMAIL_LENGTH = 55;
const MIN_USER_EMAIL_LENGTH = NULL;
const MAX_USER_PASSWORD_LENGTH = 50;
const MIN_USER_PASSWORD_LENGTH = 8;

const MIN_ROM_FILENAME_LENGTH = 3; // includes filename and extension `a.gb`
const MAX_ROM_FILENAME_LENGTH = 34; // includes filename and extension

// Database //

const BCRYPT_PASSWORD_LENGTH = 60;
const OBJECT_ID_LENGTH = 24;
const SESSION_ID_LENGTH = 40;
const IP_ADDRESS_LENGTH = 46;
const PERSONAL_ACCESS_TOKEN_LENGTH = 64;
const PERSONAL_ACCESS_TOKEN_NAME_LENGTH = 70;
const PASSWORD_RESET_TOKEN_LENGTH = 60;
const PERSONAL_ACCESS_TOKEN_TYPE_LENGTH = 96;
const MD5_HASH_LENGTH = 32;
const REMEMBER_TOKEN_LENGTH = 60;
const METADATA_CONTENT_TYPE_LENGTH = 36;
const PROFILE_PHOTO_URI_LENGTH = 2048;
