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

namespace Guanaco\Development\Infrastructure\DependencyInjection\Compiler;

use Guanaco\Development\Infrastructure\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
class InjectConfigurationPass implements CompilerPassInterface
{
    const COMPOSER_TAG = 'guanaco.development.config_aware';

    public function process(ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $container->getExtensionConfig('guanaco_development'));
        $composerServices = $container->findTaggedServiceIds(self::COMPOSER_TAG);

        foreach ($composerServices as $id => $tags) {
            $definition = $container->getDefinition($id);
            $definition->addArgument($config['infrastructure']);
        }
    }
}
