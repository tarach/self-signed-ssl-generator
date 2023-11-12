<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Tarach\SelfSignedCert\SSLGenerateCommand;

$application = new Application('ssl:generate', '1.0.0');
$command = new SSLGenerateCommand();

$application->add($command);

$application->setDefaultCommand($command->getName(), true);
$application->run();
