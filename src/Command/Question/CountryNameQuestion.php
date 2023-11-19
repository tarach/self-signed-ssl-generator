<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

class CountryNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Country Name (2 letter code - ISO 3166-1 alfa-2)';
    }

    public static function getCommandOptionName(): string
    {
        return 'un';
    }

    public static function getName(): string
    {
        return 'countryName';
    }
}