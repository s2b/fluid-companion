<?php

declare(strict_types=1);

namespace Praetorius\FluidCompanion\Parser;

/**
 * Describes a location within a template file
 * Backported from Fluid 5
 *
 * @see TYPO3Fluid\Fluid\Core\Parser\TemplateLocation
 */
final readonly class TemplateLocation
{
    /**
     * @param string $identifierOrPath  internal name or path of a template file
     * @param int $line                 line number, starting with 1
     * @param int $character            character number within line, starting with 1
     */
    public function __construct(
        public string $identifierOrPath,
        public int $line,
        public int $character,
    ) {}
}
