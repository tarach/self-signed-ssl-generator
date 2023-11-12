<?php

declare(strict_types=1);

namespace Tarach\SelfSignedCert;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationDefinition implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sslgen');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode(Config::KEY_DEFAULTS)
                    ->scalarPrototype()->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}