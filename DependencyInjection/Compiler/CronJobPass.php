<?php

namespace FDevs\Bridge\Cron\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CronJobPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('f_devs_cron.cron')) {
            $definition = $container->getDefinition('f_devs_cron.cron');
            $taggedServices = $container->findTaggedServiceIds('f_devs_cron.job');
            foreach ($taggedServices as $id => $tags) {
                $definition->addMethodCall('addJob', [new Reference($id)]);
            }
        }
    }
}
