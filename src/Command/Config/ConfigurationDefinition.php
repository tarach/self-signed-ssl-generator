<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert\Command\Config;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationDefinition implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sslgen');
        $node        = $treeBuilder->getRootNode();

        $node
            ->arrayPrototype()
            ->children()
                ->arrayNode(Config::KEY_AUTHORITY)
                    ->children()
                        ->scalarNode(Config::KEY_AUTHORITY_CERT)->isRequired()->end()
                        ->scalarNode(Config::KEY_AUTHORITY_PKEY)->isRequired()->end()
                    ->end()
                ->end()
                ->scalarNode(Config::KEY_DIRECTORY)->end()
                ->scalarNode(Config::KEY_OVERWRITE)->end()
                ->scalarNode(Config::KEY_SKIP)->end()
                ->arrayNode(Config::KEY_FILES)
                    ->children()
                        ->arrayNode(Config::KEY_CSR_FILE_NAME)
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function (string $v): array { return ['name' => $v]; })
                            ->end()
                            ->children()
                                ->scalarNode('name')->end()
                            ->end()
                        ->end()
                        ->arrayNode(Config::KEY_CERT_FILE_NAME)
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function (string $v): array { return ['name' => $v]; })
                            ->end()
                            ->children()
                                ->scalarNode('name')->end()
                            ->end()
                        ->end()
                        ->arrayNode(Config::KEY_PKEY_FILE_NAME)
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function (string $v): array { return ['name' => $v]; })
                            ->end()
                            ->children()
                                ->scalarNode('name')->end()
                                ->arrayNode('options')
                                    ->children()
                                        ->scalarNode('private_key_bits')->end()
                                        ->scalarNode('private_key_type')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode(Config::KEY_DEFAULTS)
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}