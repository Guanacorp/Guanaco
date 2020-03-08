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

use Guanaco\Development\Domain\Command\GenerateDomainCommand;
use Guanaco\Development\Domain\Command\GenerateDomainHandler as DecoratedGenerateDomainHandler;
use Guanaco\Development\Domain\Command\GenerateDomainHandlerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
class GenerateDomainHandler implements GenerateDomainHandlerInterface, MessageHandlerInterface
{
    /**
     * @var DecoratedGenerateDomainHandler
     */
    private $handler;

    public function __construct(DecoratedGenerateDomainHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(GenerateDomainCommand $command)
    {
        $handler = $this->handler;

        return $handler($command);
    }

}
