<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Question;

class OrganizationNameQuestion extends AbstractQuestion
{
    public static function getQuestionString(): string
    {
        return 'Organization Name (e.g., company)';
    }

    public static function getCommandOptionName(): string
    {
        return 'on';
    }

    public static function getName(): string
    {
        return 'organizationName';
    }
}