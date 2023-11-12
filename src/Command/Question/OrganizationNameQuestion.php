<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

use Symfony\Component\Console\Input\InputOption;

class OrganizationNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Organization Name (e.g., company)';
    }

    public static function getCommandOption(): InputOption
    {
        return new InputOption(
            'on',
            null,
            InputOption::VALUE_REQUIRED,
            self::getQuestionString(),
        );
    }

    public static function getName(): string
    {
        return 'organizationName';
    }
}