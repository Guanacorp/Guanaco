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

use Guanaco\Development\Domain\Command\GenerateInfrastructureCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Alexandre Andre <alexandre@creakiwi.com>
 */
class GenerateDomainQuery extends Command
{
    protected static $defaultName = 'guanaco:generate:domain:query';

    private $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        parent::__construct();

        $this->messageBus = $messageBus;
    }

    protected function configure()
    {
        $this
            ->setDescription('Generates Query for a Domain Driven Guanaco package')
            ->addArgument('package', InputArgument::REQUIRED, 'The package where the Query belongs to')
            ->addArgument('query', InputArgument::REQUIRED, 'The name of the Query')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $package = $input->getArgument('package');

        $this->messageBus->dispatch(new GenerateDomainCommand($package));
        $this->messageBus->dispatch(new GenerateInfrastructureCommand($package));
    }
}
