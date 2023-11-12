<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

use Symfony\Component\Console\Input\InputOption;

class CountryNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Country Name (2 letter code - ISO 3166-1 alfa-2)';
    }

    public static function getCommandOption(): InputOption
    {
        return new InputOption(
            'un',
            null,
            InputOption::VALUE_REQUIRED,
            self::getQuestionString(),
        );
    }

    public static function getName(): string
    {
        return 'countryName';
    }
}