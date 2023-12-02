<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Option;

use Symfony\Component\Console\Input\InputOption;

class SchemaOption extends AbstractInputOption
{
    public const NAME = 'schema';

    public function __construct()
    {
        parent::__construct(
            self::NAME,
            'e',
            InputOption::VALUE_REQUIRED,
            'Name or number of configuration schema to use.',
        );
    }
}