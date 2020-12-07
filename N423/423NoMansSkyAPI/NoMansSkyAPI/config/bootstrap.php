<?php
/**
 * Author: Stewart McCalley
 * Date: 12/1/2020
 * File: bootstrap.php
 * Description:
 */

//load config settings
$config = require __DIR__ . '/config.php';

//load composer autoload
require __DIR__ . '/../vendor/autoload.php';

//prepare the app
$app = new \Slim\App(['settings'=>$config]);

//add dependencies to container
require __DIR__ . '/dependencies.php';

//load service factory
require __DIR__ . '/services.php';

//customer routes
require __DIR__ . '/routes.php';