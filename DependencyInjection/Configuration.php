<?php

namespace Redeye\SidekiqBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\ExpressionLanguage\Expression;

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
                ->isRequired()
                ->children()
                    ->arrayNode('dsn')
                        ->isRequired()
                        ->beforeNormalization()
                            ->ifString()
                            ->then(function($v) { return array('scalar' => $v); })
                        ->end()
                        ->children()
                            ->scalarNode('scalar')->end()
                            ->variableNode('expression')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function($v) { return new Expression($v); })
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
