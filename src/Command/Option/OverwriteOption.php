<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Option;

use Symfony\Component\Console\Input\InputOption;
use Tarach\SelfSignedCert\Command\Config\Config;
use Tarach\SelfSignedCert\Command\Config\ConfigOverrideInterface;

class OverwriteOption extends AbstractInputOption implements ConfigOverrideInterface
{
    public function __construct()
    {
        parent::__construct(
            'overwrite',
            'o',
            InputOption::VALUE_NONE,
            'Overwrite output directory if it already exists.',
        );
    }

    public function getConfigName(): string
    {
        return Config::KEY_OVERWRITE;
    }
}