<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

class EmailAddressQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Email address';
    }

    public static function getCommandOptionName(): string
    {
        return 'ea';
    }

    public static function getName(): string
    {
        return 'emailAddress';
    }
}