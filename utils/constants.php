<?php

//--- GLOBAL CONSTANTS ---//

$constants_path = __DIR__ . '/constants';

if (!dir_is_empty($constants_path)) {
    require_once $constants_path . '/entities.inc.php';
    require_once $constants_path . '/enums.inc.php';
    require_once $constants_path . '/lengths_and_sizes.inc.php';
    require_once $constants_path . '/patterns.inc.php';
}

//--- END GLOBAL CONSTANTS ---//
