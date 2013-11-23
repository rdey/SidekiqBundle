<?php

namespace Redeye\SidekiqBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('redeye_sidekiq');

        $rootNode
            ->children()
            ->scalarNode('namespace')->isRequired()->end()
            ->arrayNode('redis')
                ->children()
                ->scalarNode('service')->defaultNull()->end()
                ->scalarNode('host')->defaultValue('127.0.0.1')->end()
                ->scalarNode('port')->defaultValue(6379)->end()
                ->scalarNode('database')->defaultNull()->end()
                ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
