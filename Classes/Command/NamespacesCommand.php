<?php

namespace Praetorius\FluidCompanion\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\ViewHelperResolverFactoryInterface;

/**
 * Lists all registered global Fluid ViewHelper namespaces
 * Backported from TYPO3 14
 *
 * @see \TYPO3\CMS\Fluid\Command\NamespacesCommand
 */
#[AsCommand('fluid:namespaces', 'Lists all registered global Fluid ViewHelper namespaces.')]
final class NamespacesCommand extends Command
{
    public function __construct(private readonly ViewHelperResolverFactoryInterface $viewHelperResolverFactory)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'json',
            null,
            InputOption::VALUE_NONE,
            'Output namespaces as JSON',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $globalNamespaces = $this->viewHelperResolverFactory->create()->getNamespaces();

        if ($input->getOption('json')) {
            $output->writeln(json_encode($globalNamespaces));
            return Command::SUCCESS;
        }

        $table = new Table($output);
        $table->setHeaders(['Alias', 'Namespace(s)']);
        $isFirst = true;
        foreach ($globalNamespaces as $alias => $namespaceChain) {
            if (!$isFirst) {
                $table->addRow(new TableSeparator());
            }
            $table->addRow([
                $alias,
                new TableCell(implode("\n", $namespaceChain), ['rowspan' => count($namespaceChain)]),
            ]);
            $isFirst = false;
        }
        $table->render();
        return Command::SUCCESS;
    }
}
