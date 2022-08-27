<?php

/*
 * Validation Size Values
 */

/** Unit: _`KB/1024`_, value: **`17` Gibibytes** */
const MAX_ROM_SIZE = 17825792;
const MIN_ROM_SIZE = 1020;

const MAX_GAME_GENERATION_VALUE = 9;
const MIN_GAME_GENERATION_VALUE = 0;

/** Unit: _`Bytes`_, value: **`17` Gibibytes** */
const MAX_ROM_FILE_SIZE = 0x440000000;
/** Unit: _`Bytes`_, value: **`1020` Kibibytes** */
const MIN_ROM_FILE_SIZE = 0xFF000;

/*
Other (rom) filesize max entity representations:

binary
0100 0100 0000 0000 0000 0000 0000 0000 0000
0b010001000000000000000000000000000000

octal
210 000 000 000
0o210000000000

decimal
18,253,611,008
18253611008
*/
