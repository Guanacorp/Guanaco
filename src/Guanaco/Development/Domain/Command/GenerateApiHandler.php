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

use Guanaco\Development\Domain\Service\InitializePackageService;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
final class GenerateApiHandler implements GenerateApiHandlerInterface
{
    /**
     * @var InitializePackageService
     */
    private $service;

    public function __construct(InitializePackageService $service)
    {
        $this->service = $service;
    }

    public function __invoke(GenerateApiCommand $command)
    {
        $this->service->handle($command);
    }
}
