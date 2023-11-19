<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

class StateOrProvinceNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'State or province';
    }

    public static function getCommandOptionName(): string
    {
        return 'sp';
    }

    public static function getName(): string
    {
        return 'stateOrProvinceName';
    }
}