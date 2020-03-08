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
final class GenerateApiCommand extends GenerateCommand
{
    protected static $directories = [
        'DependencyInjection',
        'Resources/config',
        'Controller',
        'Tests',
    ];

    public function getType(): string
    {
        return self::TYPE_API;
    }
}
