<?php

declare(strict_types=1);

namespace Praetorius\FluidCompanion\Validation;

/**
 * Backported from Fluid 5
 *
 * @see \TYPO3Fluid\Fluid\Validation\Deprecation
 */
final readonly class Deprecation
{
    public function __construct(
        public string $file,
        public int $line,
        public string $message,
    ) {}
}
