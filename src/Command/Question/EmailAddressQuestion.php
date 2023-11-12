<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

use Symfony\Component\Console\Input\InputOption;

class EmailAddressQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Email address';
    }

    public static function getCommandOption(): InputOption
    {
        return new InputOption(
            'ea',
            null,
            InputOption::VALUE_REQUIRED,
            self::getQuestionString(),
        );
    }

    public static function getName(): string
    {
        return 'emailAddress';
    }
}