<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Tarach\SelfSignedCert\Command\QuestionCollectionFactory;

readonly class CommandLineOptionsConfigLoader
{
    use ArrayHelperTrait;

    public function __construct(
        private QuestionCollectionFactory $questionFactory
    ) {
    }

    public function load(InputInterface $input, InputDefinition $inputDefinition): array
    {
        $config = [];

        foreach ($this->getUsedOptions($input) as $optionName)
        {
            $option = $inputDefinition->getOption($optionName);
            if (!($option instanceof ConfigOverrideInterface)) {
                continue;
            }

            $element =& $this->getElementInArrayToSet($config, explode('.', $option->getConfigName()));
            $element = $input->getOption($option->getName());
        }

        foreach ($this->questionFactory->getMap() as $name => $question) {
            $option = call_user_func([$question, 'getCommandOption']);
            assert($option instanceof InputOption);
            $optionName = $option->getName();
            $value = $input->getOption($optionName);

            if (is_null($value)) {
                continue;
            }

            $config[Config::KEY_DEFAULTS][$name] = $value;
        }

        return $config;
    }

    private function getUsedOptions(InputInterface $input): array
    {
        return array_keys(
            (new \ReflectionObject($input))
                ->getProperty('options')
                ->getValue($input)
        );
    }
}