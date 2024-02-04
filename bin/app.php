<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Tarach\SelfSignedCert\SSLGenerateCommand;

$application = new Application('ssl:generate', '1.0.5');
$command = new SSLGenerateCommand();

$application->add($command);

$application->setDefaultCommand($command->getName(), true);

return $application;