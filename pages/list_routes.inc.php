<?php

$output = /** @lang Shell Script */
    `cd .. && php artisan route:list`;
echo "<pre>" . htmlentities($output) . "</pre>";
