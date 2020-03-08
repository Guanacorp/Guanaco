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
interface GenerateCommandInterface
{
    const TYPE_DOMAIN = 'Domain';
    const TYPE_INFRASTRUCTURE = 'Infastructure';
    const TYPE_API = 'Api';

    public function getType(): string;

    public function getPackageType(): string;

    public function getPackageName(): string;

    public function getDirectories(): array;

    public function getPackageDirectory();
}
