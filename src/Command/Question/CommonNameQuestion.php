<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

class CommonNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Common Name (e.g., server FQDN)';
    }

    public static function getCommandOptionName(): string
    {
        return 'cn';
    }

    public static function getName(): string
    {
        return 'commonName';
    }
}