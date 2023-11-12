<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tarach\SelfSignedCert\Command\QuestionCollectionFactory;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * @method getName(): string
 */
class SSLGenerateCommand extends Command
{
    public const RETURN_INVALID_OUTPUT = 3;

    private string $defaultOutputDirectory;
    private QuestionCollectionFactory $questionFactory;

    public function __construct()
    {
        parent::__construct('ssl:generate');

        $this->defaultOutputDirectory = getcwd() . DIRECTORY_SEPARATOR . 'ssl-cert';
        $this->questionFactory = new QuestionCollectionFactory();

        foreach ($this->questionFactory->getMap() as $question) {
            $this->getDefinition()->addOption(call_user_func([$question, 'getCommandOption']));
        }

        $this->addOption(
            'directory',
            'd',
            InputOption::VALUE_REQUIRED,
            'Path to output directory.',
            $this->defaultOutputDirectory
        );

        $this->addOption(
            'config',
            'c',
            InputOption::VALUE_REQUIRED,
            'Path to yaml configuration file(s). Or multiple comma separated files.',
            implode(',', $this->getConfigPaths())
        );

        $this->addOption(
            'overwrite',
            'o',
            InputOption::VALUE_NONE,
            'Overwrite output directory if it already exists.'
        );

        $this->addOption(
            'authority',
            'a',
            InputOption::VALUE_NONE,
            'Don\'t create certificate authority (CA) and use this one instead.'
        );

        $this->addOption(
            'skip',
            's',
            InputOption::VALUE_NONE,
            'Don\'t confirm with question if default value is set.'
        );
    }

    public function createDistinguishedNames(Config $config, bool $skip, InputInterface $input, OutputInterface $output): DistinguishedNames
    {
        $helper = $this->getHelper('question');

        $names = [];
        foreach ($this->questionFactory->create($config) as $question) {
            if ($skip && $question->hasDefault()) {
                $names[$question->getName()] = $question->getDefault();
                continue;
            }
            $names[$question->getName()] = $helper->ask($input, $output, $question);
        }
        return new DistinguishedNames(...$names);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $directory = $input->getOption('directory');
        $overwrite = $input->getOption('overwrite');

        if (file_exists($directory)) {
            if (!is_dir($directory)) {
                $output->writeln(sprintf('<error>Path [%s] is not a directory.</error>', $directory));
                return self::RETURN_INVALID_OUTPUT;
            }

            if (!$overwrite) {
                $output->writeln(sprintf('<info>Directory [%s] already exists. Use -o option to force overwrite.</info>', $directory));
                return self::SUCCESS;
            }

            $output->writeln(sprintf('<info>Directory [%s] already exists. Overwriting...</info>', $directory));
        }

        $config = $this->loadConfig($input->getOption('config'), $input);

        $skip = $input->getOption('skip');

        $names = $this->createDistinguishedNames($config, $skip, $input, $output);

        $service = new SSLGeneratorService();
        $ssl = $service->generate($names);

        $exporter = new SSLExporterService();
        $exporter->toFiles($ssl, $directory);

        return self::SUCCESS;
    }

    private function getConfigPaths(): array
    {
        return [
            getcwd() . DIRECTORY_SEPARATOR . 'sslgen.yaml',
            $_SERVER['HOME'] . DIRECTORY_SEPARATOR . '.sslgen' . DIRECTORY_SEPARATOR . 'config.yaml',
            getenv('SSL_GEN_CONFIG'),
        ];
    }

    private function loadConfig(string $configsString, InputInterface $input): Config
    {
        $configs = [];
        foreach (explode(',', $configsString) as $path)
        {
            if (!file_exists($path)) {
                continue;
            }

            $configs[] = Yaml::parse(
                file_get_contents($path)
            );
        }

        $configs[] = $this->loadConfigFromOptions($input);

        $processor = new Processor();
        $definition = new ConfigurationDefinition();
        $processedConfiguration = $processor->processConfiguration(
            $definition,
            $configs
        );

        return new Config($processedConfiguration);
    }

    private function loadConfigFromOptions(InputInterface $input): array
    {
        $config = [
            Config::KEY_DEFAULTS => []
        ];

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
}