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

        $loader = new Loader\XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.xml');

        $pusher = $container->getDefinition('redeye_sidekiq');
        $pusher->replaceArgument(1, $config['namespace']);

        $this->configurePredis(
            $container->getDefinition('redeye_sidekiq.predis'),
            $config['redis']
        );
    }

    private function configurePredis($predis, $config)
    {
        $argument = [
            'host' => $config['host'],
            'port' => $config['port'],
        ];
        if (isset($config['password'])) {
            $argument['password'] = $config['password'];
        }
        if (isset($config['database'])) {
            $argument['database'] = $config['database'];
        }

        $predis->replaceArgument(0, $argument);
    }
}
