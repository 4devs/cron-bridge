<?php

namespace FDevs\Bridge\Cron\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('f_devs_cron');
        $rootNode
            ->children()
                ->arrayNode('exporter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('key')->defaultValue('f_devs_cron')->example('generated')->end()
                        ->scalarNode('mailto')->example('cron@example.com')->end()
                        ->scalarNode('path')->defaultValue('/usr/local/bin:/usr/bin:/bin')->example('/usr/local/bin:/usr/bin:/bin')->end()
                        ->scalarNode('executor')->defaultValue('php')->example('php')->end()
                        ->scalarNode('console')->defaultValue('bin/console')->example('bin/console(symfony 3.0)')->end()
                        ->scalarNode('shell')->example('/bin/sh')->end()
                    ->end()
                ->end()
                ->arrayNode('commands')
                    ->useAttributeAsKey('name')
                    ->defaultValue([])
                    ->prototype('array')
                        ->children()
                            ->scalarNode('command')->isRequired()->example('swiftmailer:spool:send')->end()
                            ->scalarNode('minute')->defaultValue('*')->example('*/5 - Every 5 minutes')->end()
                            ->scalarNode('hour')->defaultValue('*')->example('8 - 5 minutes past 8am every day')->end()
                            ->scalarNode('day_of_week')->defaultValue('*')->example('0 - 5 minutes past 8am every Sunday')->end()
                            ->scalarNode('day')->defaultValue('*')->example('1 -  5 minutes past 8am on first of each month')->end()
                            ->scalarNode('month')->defaultValue('*')->example('1 - 5 minutes past 8am on first of of January')->end()
                            ->scalarNode('log_file')->example('%kernel.logs_dir%/%kernel.environment%_cron.log')->end()
                            ->scalarNode('error_file')->example('%kernel.logs_dir%/%kernel.environment%_error.log')->end()
                            ->scalarNode('params')->defaultValue('')->example('--color=red')->end()
                            ->scalarNode('executor')->info('add if use custom executor')->example('/usr/bin/php')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
