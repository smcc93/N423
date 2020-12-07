<?php
/**
 * Author: Stewart McCalley
 * Date: 12/1/2020
 * File: config.php
 * Description:
 */


return [
    //display error details in dev environment
    'displayErrorDetails' => true,

    //database connection details
    'db' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'nomansskydb',
        'username' => 'phpuser',
        'password' => 'phpuser',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => ''
    ]
];