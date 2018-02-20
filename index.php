#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 20.02.18
 * Time: 15:27
 */
require 'vendor/autoload.php';

use Classes\ApplicationFactory;

/* Check if params passed */
if (!isset($argv[1])) {
    echo 'Please provide website url!';

    return;
}

$factory = new ApplicationFactory();
$application = $factory->createApplication();

echo $application->run($argv[1]);
