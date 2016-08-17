<?php

namespace Redeye\SidekiqBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RedeyeSidekiqExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');

        $pusher = $container->getDefinition('redeye_sidekiq');
        $pusher->replaceArgument(1, $config['namespace']);

        if ($config['redis']['dsn']['expression']) {
            $dsn = $config['redis']['dsn']['expression'];
        } else {
            $dsn = $config['redis']['dsn']['scalar'];
        }

        $container
            ->getDefinition('redeye_sidekiq.predis')
            ->replaceArgument(0, $dsn)
        ;
    }
}
