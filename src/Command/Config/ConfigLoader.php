<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Yaml\Yaml;
use Tarach\SelfSignedCert\Command\OptionsCollection;
use Tarach\SelfSignedCert\Command\QuestionCollectionFactory;

readonly class ConfigLoader
{
    public function __construct(
        private QuestionCollectionFactory $questionFactory,
        private OptionsCollection $options,
    ){
    }

    public function load(string $configsString, InputInterface $input, InputDefinition $inputDefinition): Config
    {
        $configs = [];

        // Load config files
        foreach (explode(',', $configsString) as $path)
        {
            if (!file_exists($path)) {
                continue;
            }

            $configs[] = Yaml::parse(
                file_get_contents($path),
                Yaml::PARSE_CONSTANT
            );
        }

        // Load default value as first element
        array_unshift($configs, $this->loadDefaultValues($input));

        // Load configuration from options
        $configs[] = $this->loadConfigFromOptions($input, $inputDefinition);

        $processor = new Processor();
        $definition = new ConfigurationDefinition();
        $processedConfiguration = $processor->processConfiguration(
            $definition,
            $configs
        );

        return new Config($processedConfiguration);
    }

    private function loadConfigFromOptions(InputInterface $input, InputDefinition $inputDefinition): array
    {
        $config = [];

        foreach ($this->getUsedOptions($input) as $optionName => $value)
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
    
    private function loadDefaultValues(InputInterface $input): array
    {
        $config = [];
        foreach ($this->options as $option)
        {
            if (!($option instanceof ConfigOverrideInterface)) {
                continue;
            }
            if (!$option->isDefaultValueSet()) {
                continue;
            }

            $element =& $this->getElementInArrayToSet($config, explode('.', $option->getConfigName()));
            $element = $input->getOption($option->getName());
        }
        return $config;
    }

    private function getUsedOptions(InputInterface $input): array
    {
        return (new \ReflectionObject($input))->getProperty('options')->getValue($input);
    }

    private function &getElementInArrayToSet(array &$config, array $path)
    {
        $key = array_shift($path);
        if (!array_key_exists($key, $config)) {
            $config[$key] = [];
        }

        if (0 === count($path)) {
            return $config[$key];
        }

        return $this->getElementInArrayToSet($config[$key], $path);
    }
}