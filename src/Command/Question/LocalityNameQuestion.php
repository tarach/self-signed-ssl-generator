<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

class LocalityNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Locality name (e.g., city)';
    }

    public static function getCommandOptionName(): string
    {
        return 'ln';
    }

    public static function getName(): string
    {
        return 'localityName';
    }
}