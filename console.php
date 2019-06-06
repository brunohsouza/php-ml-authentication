#!/usr/bin/php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use App\Command\AuthCommandController;

$application = new Application();
$application->add(new AuthCommandController($application));

try {
    $application->run();
} catch (Exception $exception) {
    return $exception->getMessage();
}
