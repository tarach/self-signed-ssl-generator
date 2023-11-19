<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Option;

use Symfony\Component\Console\Input\InputOption;

class ConfigOption extends AbstractInputOption
{
    public function __construct(string|bool|int|float|array $default = null)
    {
        parent::__construct(
            'config',
            'c',
            InputOption::VALUE_REQUIRED,
            'Path to yaml configuration file(s). Or multiple comma separated files.',
            $default,
        );
    }
}