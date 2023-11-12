<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

use Symfony\Component\Console\Input\InputOption;

class LocalityNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Locality name (e.g., city)';
    }

    public static function getCommandOption(): InputOption
    {
        return new InputOption(
            'ln',
            null,
            InputOption::VALUE_REQUIRED,
            self::getQuestionString(),
        );
    }

    public static function getName(): string
    {
        return 'localityName';
    }
}