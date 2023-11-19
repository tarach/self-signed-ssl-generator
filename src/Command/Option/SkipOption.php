<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Option;

use Symfony\Component\Console\Input\InputOption;
use Tarach\SelfSignedCert\Command\Config\Config;
use Tarach\SelfSignedCert\Command\Config\ConfigOverrideInterface;

class SkipOption extends AbstractInputOption implements ConfigOverrideInterface
{
    public function __construct()
    {
        parent::__construct(
            'skip',
            's',
            InputOption::VALUE_NONE,
            'Don\'t confirm with question if default value is set.',
        );
    }

    public function getConfigName(): string
    {
        return Config::KEY_SKIP;
    }
}