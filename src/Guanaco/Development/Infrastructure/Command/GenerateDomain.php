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

namespace Guanaco\Development\Infrastructure\Command;

use Guanaco\Development\Domain\Command\GenerateDomainCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
class GenerateDomain extends Command
{
    protected static $defaultName = 'guanaco:generate:domain';

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        parent::__construct();

        $this->messageBus = $messageBus;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generates Domain for a Domain Driven Guanaco package')
            ->addArgument('package', InputArgument::REQUIRED, 'The name of the package to generate Domain')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->messageBus->dispatch(new GenerateDomainCommand($input->getArgument('package')));

        return 0;
    }
}
