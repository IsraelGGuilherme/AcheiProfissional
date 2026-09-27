<?php

$routesFiles = [
    'Public',
    'Auth',
];

foreach ($routesFiles as $filename) {
    if (file_exists(APPPATH . "Config/Routes/$filename.php")) {
        require APPPATH . "Config/Routes/$filename.php";
    }
}

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}