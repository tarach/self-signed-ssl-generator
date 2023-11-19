<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Option;

use Symfony\Component\Console\Input\InputOption;
use Tarach\SelfSignedCert\Command\Config\Config;
use Tarach\SelfSignedCert\Command\Config\ConfigOverrideInterface;

class DirectoryOption extends AbstractInputOption implements ConfigOverrideInterface
{
    public function __construct(string|bool|int|float|array $default = null)
    {
        parent::__construct(
            'directory',
            'd',
            InputOption::VALUE_REQUIRED,
            'Path to output directory.',
            $default,
        );
    }

    public function getConfigName(): string
    {
        return Config::KEY_DIRECTORY;
    }
}