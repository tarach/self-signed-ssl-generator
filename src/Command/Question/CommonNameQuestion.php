<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

use Symfony\Component\Console\Input\InputOption;

class CommonNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Common Name (e.g., server FQDN)';
    }

    public static function getCommandOption(): InputOption
    {
        return new InputOption(
            'cn',
            null,
            InputOption::VALUE_REQUIRED,
            self::getQuestionString(),
        );
    }

    public static function getName(): string
    {
        return 'commonName';
    }
}