<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command;

use Tarach\SelfSignedCert\Command\Option\AuthorityCertificateOption;
use Tarach\SelfSignedCert\Command\Option\AuthorityPrivateKeyOption;
use Tarach\SelfSignedCert\Command\Option\ConfigOption;
use Tarach\SelfSignedCert\Command\Option\DirectoryOption;
use Tarach\SelfSignedCert\Command\Option\OverwriteOption;
use Tarach\SelfSignedCert\Command\Option\SchemaOption;
use Tarach\SelfSignedCert\Command\Option\SkipOption;

class OptionsCollectionFactory
{
    private array $map;

    public function __construct()
    {
        $this->map = [
            AuthorityPrivateKeyOption::class,
            AuthorityCertificateOption::class,
            OverwriteOption::class,
            SkipOption::class,
            SchemaOption::class,
        ];
    }

    public function create(string $outputDirectory, string $defaultConfigOption): OptionsCollection
    {
        $options = [
            new ConfigOption($defaultConfigOption),
            new DirectoryOption($outputDirectory)
        ];

        foreach ($this->map as $class) {
            $options[] = new $class();
        }

        return new OptionsCollection($options);
    }


}