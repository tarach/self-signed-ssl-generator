<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

use Symfony\Component\Console\Input\InputOption;

class OrganizationalUnitNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Organization Unit Name (e.g., section)';
    }

    public static function getCommandOption(): InputOption
    {
        return new InputOption(
            'oun',
            null,
            InputOption::VALUE_REQUIRED,
            self::getQuestionString(),
        );
    }

    public static function getName(): string
    {
        return 'organizationalUnitName';
    }
}