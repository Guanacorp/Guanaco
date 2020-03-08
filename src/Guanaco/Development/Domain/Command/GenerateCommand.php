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

use Webmozart\Assert\Assert;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
abstract class GenerateCommand implements GenerateCommandInterface
{
    private static $types = [
        self::TYPE_DOMAIN => 'library',
        self::TYPE_INFRASTRUCTURE => 'symfony-bundle',
        self::TYPE_API => 'symfony-bundle',
    ];

    protected static $directories = [];

    /**
     * @var string
     */
    private $packageName;

    public function __construct(string $packageName, array $additionalDirectories = [], array $removeDirectories = [])
    {
        $this->packageName = $packageName;
        Assert::oneOf($this->getType(), array_keys(self::$types));
        static::$directories = array_map(function($directory) {
            return sprintf('%s/%s', $this->getPackageDirectory(), $directory);
        }, array_diff(array_merge(static::$directories, $additionalDirectories), $removeDirectories));
    }

    abstract public function getType(): string;

    public function getPackageName(): string
    {
        return $this->packageName;
    }

    public function getDirectories(): array
    {
        return static::$directories;
    }

    public function getPackageDirectory()
    {
        return sprintf('%s/%s', $this->packageName, $this->getType());
    }

    public function getPackageType(): string
    {
        return self::$types[$this->getType()];
    }
}
