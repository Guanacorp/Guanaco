<?php
/*
 * This file is part of the Guanaco package.
 *
 * (c) Alexandre Andre <alexandre@creakiwi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Guanaco\Development\Infrastructure\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('guanaco_development');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('root_directory')->defaultValue('src/Guanaco')->cannotBeEmpty()->end()
                ->arrayNode('authors')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('name')->end()
                            ->scalarNode('email')->end()
                        ->end()
                    ->end()
                ->end() // authors
                ->arrayNode('composer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('license')->defaultValue('MIT')->cannotBeEmpty()->end()
                        ->arrayNode('requirements')
                            ->children()
                                ->arrayNode('require')
                                    ->arrayPrototype()
                                        ->children()
                                            ->scalarNode('package')->end()
                                            ->scalarNode('version')->end()
                                        ->end()
                                    ->end()
                                ->end() // require
                                ->arrayNode('require-dev')
                                    ->arrayPrototype()
                                        ->children()
                                            ->scalarNode('package')->end()
                                            ->scalarNode('version')->end()
                                        ->end()
                                    ->end()
                                ->end() // require-dev
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
