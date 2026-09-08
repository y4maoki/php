<?php

spl_autoload_register(function ($class_name) {
    $dirs = [
        '../framework/',
        '../controllers/',
        '../middlewares/' 
    ];

    foreach ($dirs as $dir) {
        $file = $dir . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});