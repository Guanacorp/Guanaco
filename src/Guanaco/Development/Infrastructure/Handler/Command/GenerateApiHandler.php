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

use Guanaco\Development\Domain\Command\GenerateApiCommand;
use Guanaco\Development\Domain\Command\GenerateApiHandler as DecoratedGenerateApiHandler;
use Guanaco\Development\Domain\Command\GenerateApiHandlerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
class GenerateApiHandler implements GenerateApiHandlerInterface, MessageHandlerInterface
{
    private $handler;

    public function __construct(DecoratedGenerateApiHandler $handler)
    {
        $this->handler = $handler;
    }

    public function __invoke(GenerateApiCommand $command)
    {
        $handler = $this->handler;

        return $handler($command);
    }
}
