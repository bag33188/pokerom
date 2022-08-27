<?php

//--- GLOBAL HELPER FUNCTIONS ---//

$functions_path = __DIR__ . '/functions';

if (!dir_is_empty($functions_path)) {
    require_once $functions_path . '/parse_date_as_readable_string.php';
    require_once $functions_path . '/normalize_object_using_json_conversions.php';
    require_once $functions_path . '/number_to_roman.php';
    require_once $functions_path . '/str_to_bool.php';
    require_once $functions_path . '/str_capitalize.php';
    require_once $functions_path . '/join_css_classes_into_html_string.php';
}

//--- END GLOBAL HELPER FUNCTIONS ---//
