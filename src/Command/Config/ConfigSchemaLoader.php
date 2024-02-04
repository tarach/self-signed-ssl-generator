<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Yaml\Yaml;
use Tarach\SelfSignedCert\Command\OptionsCollection;
use Tarach\SelfSignedCert\Command\QuestionCollectionFactory;

readonly class ConfigSchemaLoader
{
    use ArrayHelperTrait;

    public function __construct(
        private QuestionCollectionFactory $questionFactory,
        private OptionsCollection $options,
        private array $commandLineOptions,
    ){
    }

    public function load(string $configsString, InputInterface $input): ConfigSchema
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

        $configFromFiles = $this->processConfig($configs);
        $schemas = $configFromFiles->getAllSchemas();

        // Load configuration from options
        $configs[] = $this->applyToSchemas(
            $schemas,
            $this->commandLineOptions
        );

        // Load default configuration
        array_unshift($configs, $this->applyToSchemas(
            $schemas,
            $this->loadDefaultValues($input))
        );
        return $this->processConfig($configs);
    }

    public function getCommandLineOptions(): array
    {
        return $this->commandLineOptions;
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

    private function processConfig(array $configs): ConfigSchema
    {
        $processor = new Processor();
        $definition = new ConfigurationDefinition();
        $processedConfiguration = $processor->processConfiguration(
            $definition,
            $configs
        );

        return new ConfigSchema($processedConfiguration);
    }

    private function applyToSchemas(array $schemas, array $configOptions): array
    {
        if (empty($configOptions)) {
            return [];
        }

        $config = [];
        foreach ($schemas as $schema)
        {
            $config[$schema] = $configOptions;
        }

        return $config;
    }
}