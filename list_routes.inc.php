<?php
$output = `php artisan route:list`;
echo "<pre>" . htmlentities($output) . "</pre>";
