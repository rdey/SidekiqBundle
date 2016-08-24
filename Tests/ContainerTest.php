<?php

namespace Redeye\SidekiqBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Yaml\Yaml;

class ContainerTest extends WebTestCase
{
    public function setUp()
    {
        $this->bootKernel();
    }

    public function testServices()
    {
        self::$kernel->boot();

        $file = self::$kernel->getContainer()->get('kernel')->getRootDir().'/test_kernel_config.yml';

        if (!is_file($file)) {
            $this->markTestSkipped();
        }

        foreach ($this->extractServices($file) as $service) {
            $this->assertNotNull(self::$kernel->getContainer()->get($service));
        }
    }

    private function extractServices($file)
    {
        $services = [];

        $data = Yaml::parse($file);

        if (isset($data['services'])) {
            foreach ($data['services'] ?: [] as $service => $definition) {
                if (
                    (!isset($definition['abstract']) && !isset($definition['public'])) ||
                    (isset($definition['abstract']) && $definition['abstract'] != true) ||
                    (isset($definition['public']) && $definition['public'] != false)
                ) {
                    $services[] = $service;
                }
            }
        }

        if (isset($data['imports'])) {
            foreach ($data['imports'] ?: [] as $import) {
                $services = array_merge(
                    $services,
                    $this->extractServices(dirname($file).'/'.$import['resource'])
                );
            }
        }

        return $services;
    }
}
