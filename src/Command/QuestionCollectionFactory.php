<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command;

use Tarach\SelfSignedCert\Command\Question\CommonNameQuestion;
use Tarach\SelfSignedCert\Command\Question\CountryNameQuestion;
use Tarach\SelfSignedCert\Command\Question\EmailAddressQuestion;
use Tarach\SelfSignedCert\Command\Question\LocalityNameQuestion;
use Tarach\SelfSignedCert\Command\Question\OrganizationalUnitNameQuestion;
use Tarach\SelfSignedCert\Command\Question\OrganizationNameQuestion;
use Tarach\SelfSignedCert\Command\Question\StateOrProvinceNameQuestion;
use Tarach\SelfSignedCert\Command\Config\Config;

class QuestionCollectionFactory
{
    private array $map;

    public function __construct()
    {
        $this->map = [
            CountryNameQuestion::getName()          => CountryNameQuestion::class,
            StateOrProvinceNameQuestion::getName()  => StateOrProvinceNameQuestion::class,
            LocalityNameQuestion::getName()         => LocalityNameQuestion::class,
            OrganizationNameQuestion::getName()     => OrganizationNameQuestion::class,
            OrganizationalUnitNameQuestion::getName() => OrganizationalUnitNameQuestion::class,
            CommonNameQuestion::getName()           => CommonNameQuestion::class,
            EmailAddressQuestion::getName()         => EmailAddressQuestion::class,
        ];
    }

    public function getMap(): array
    {
        return $this->map;
    }

    public function create(Config $config): QuestionCollection
    {
        $questions = [];
        foreach ($this->map as $name => $class)
        {
            if ($config->hasDefault($name)) {
                $questions[] = new $class($config->getDefault($name));
                continue;
            }

            $questions[] = new $class();
        }

        return new QuestionCollection($questions);
    }
}