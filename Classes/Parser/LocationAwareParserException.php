<?php

declare(strict_types=1);

namespace Praetorius\FluidCompanion\Parser;

use Throwable;
use TYPO3Fluid\Fluid\Core\Parser\Exception;

/**
 * Based on changes in Fluid 5
 *
 * @see \TYPO3Fluid\Fluid\Core\TemplateLocationException
 */
class LocationAwareParserException extends Exception
{
    public function __construct(
        string $message,
        int $code = 0,
        ?Throwable $previous = null,
        protected readonly ?TemplateLocation $templateLocation = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getTemplateLocation(): TemplateLocation
    {
        return $this->templateLocation ?? new TemplateLocation('', 1, 1);
    }
}
