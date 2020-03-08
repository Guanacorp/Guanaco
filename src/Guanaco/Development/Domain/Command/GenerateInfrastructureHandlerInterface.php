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

namespace Guanaco\Development\Domain\Command;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
interface GenerateInfrastructureHandlerInterface
{
    public function __invoke(GenerateInfrastructureCommand $command);
}
