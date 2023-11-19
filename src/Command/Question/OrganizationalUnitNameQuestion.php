<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

class OrganizationalUnitNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Organization Unit Name (e.g., section)';
    }

    public static function getCommandOptionName(): string
    {
        return 'oun';
    }

    public static function getName(): string
    {
        return 'organizationalUnitName';
    }
}