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

namespace Guanaco\Development\Infrastructure;

use Guanaco\Development\Infrastructure\DependencyInjection\Compiler\InjectConfigurationPass;
use Guanaco\Development\Infrastructure\DependencyInjection\GuanacoDevelopmentExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
final class GuanacoDevelopmentInfrastructureBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new InjectConfigurationPass());
    }

    public function getContainerExtension()
    {
        return new GuanacoDevelopmentExtension();
    }
}
