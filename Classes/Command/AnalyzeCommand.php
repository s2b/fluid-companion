<?php

declare(strict_types=1);

namespace Praetorius\FluidCompanion\Command;

use Praetorius\FluidCompanion\Validation\TemplateValidator;
use Praetorius\FluidCompanion\Validation\TemplateValidatorResult;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;

/**
 * Analyzes Fluid templates for syntax errors and deprecated functionality
 * Partially backported from TYPO3 14
 *
 * @see \TYPO3\CMS\Fluid\Command\AnalyzeCommand
 */
#[AsCommand(
    'fluid:analyze',
    'Analyzes Fluid templates for syntax errors and deprecated functionality.',
    ['fluid:analyse'],
)]
final class AnalyzeCommand extends Command
{
    public function __construct(private readonly RenderingContextFactory $renderingContextFactory)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'stdin',
            null,
            InputOption::VALUE_NONE,
            'Analyze template string that is provided via STDIN',
        );
        $this->addOption(
            'json',
            null,
            InputOption::VALUE_NONE,
            'Output results as JSON',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$input->getOption('stdin') || !$input->getOption('json')) {
            throw new \InvalidArgumentException('Only --stdin and --json have been backported.', 1775940192);
        }
        $result = $this->validateTemplateFiles(['php://stdin']);
        $result = $input->getOption('stdin') ? $result['php://stdin'] : $result;
        $output->writeln(json_encode($result));
        return Command::SUCCESS;
    }

    /**
     * @return TemplateValidatorResult[]
     */
    private function validateTemplateFiles(array $templates): array
    {
        return (new TemplateValidator())->validateTemplateFiles(
            $templates,
            $this->renderingContextFactory->create(),
        );
    }
}
