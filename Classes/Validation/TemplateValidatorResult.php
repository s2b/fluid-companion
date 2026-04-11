<?php

declare(strict_types=1);

namespace Praetorius\FluidCompanion\Validation;

use Praetorius\FluidCompanion\Parser\LocationAwareParserException;
use TYPO3Fluid\Fluid\Core\Parser\ParsingState;

/**
 * Backported from Fluid 5 with small adjustments
 *
 * @see \TYPO3Fluid\Fluid\Validation\TemplateValidatorResult
 */
final readonly class TemplateValidatorResult implements \JsonSerializable
{
    /**
     * @param \Exception[] $errors
     * @param Deprecation[] $deprecations
     */
    public function __construct(
        public string $identifier,
        public string $path,
        public array $errors,
        public array $deprecations,
        public ?ParsingState $parsedTemplate,
    ) {}

    /**
     * Creates a copy with different errors. This allows
     * to attach errors after the object has been created,
     * e. g. errors happening during template compilation
     *
     * @param \Exception[] $errors
     */
    public function withErrors(array $errors): self
    {
        return new self(
            identifier: $this->identifier,
            path: $this->path,
            errors: $errors,
            deprecations: $this->deprecations,
            parsedTemplate: $this->parsedTemplate,
        );
    }

    public function canBeCompiled(): bool
    {
        return $this->errors === [] && $this->parsedTemplate?->isCompilable();
    }

    public function jsonSerialize(): array
    {
        return [
            'identifier' => $this->identifier,
            'path' => $this->path,
            'errors' => array_map(fn($e) => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
                ...($e instanceof LocationAwareParserException ? ['templateLocation' => get_object_vars($e->getTemplateLocation())] : []),
            ], $this->errors),
            'deprecations' => array_map(fn($deprecation) => get_object_vars($deprecation), $this->deprecations),
        ];
    }
}
