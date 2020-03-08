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

namespace Guanaco\Development\Infrastructure\Handler\Command;

use Guanaco\Development\Domain\Command\GenerateInfrastructureCommand;
use Guanaco\Development\Domain\Command\GenerateInfrastructureHandlerInterface;
use Guanaco\Development\Domain\Command\GenerateInfrastructureHandler as DecoratedGenerateInfrastructureHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
class GenerateInfrastructureHandler implements GenerateInfrastructureHandlerInterface, MessageHandlerInterface
{
    private $handler;

    public function __construct(DecoratedGenerateInfrastructureHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(GenerateInfrastructureCommand $command)
    {
        $handler = $this->handler;

        return $handler($command);
    }
}
