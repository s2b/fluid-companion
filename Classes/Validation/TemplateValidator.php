<?php

declare(strict_types=1);

namespace Praetorius\FluidCompanion\Validation;

use Praetorius\FluidCompanion\Parser\LocationAwareParserException;
use Praetorius\FluidCompanion\Parser\TemplateLocation;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContext;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Backported from Fluid 5 with small adjustments
 *
 * @see \TYPO3Fluid\Fluid\Validation\TemplateValidator
 */
final readonly class TemplateValidator
{
    /**
     * Collects deprecations and exceptions during parsing and compilation
     * of the supplied Fluid template files
     *
     * @param string[] $templates
     * @return TemplateValidatorResult[]
     */
    public function validateTemplateFiles(array $templates, ?RenderingContextInterface $baseRenderingContext = null): array
    {
        $baseRenderingContext ??= new RenderingContext();
        $results = [];
        foreach ($templates as $template) {
            $deprecations = $errors = [];
            set_error_handler(
                function (int $errno, string $errstr, string $errfile, int $errline) use (&$deprecations): bool {
                    $deprecations[] = new Deprecation($errfile, $errline, $errstr);
                    return true;
                },
                E_USER_DEPRECATED,
            );

            $renderingContext = clone $baseRenderingContext;
            $viewHelperResolver = method_exists($renderingContext->getViewHelperResolver(), 'getScopedCopy')
                ? $renderingContext->getViewHelperResolver()->getScopedCopy()
                : $renderingContext->getViewHelperResolver();
            $renderingContext->setViewHelperResolver($viewHelperResolver);
            $templatePaths = $renderingContext->getTemplatePaths();
            $templatePaths->setTemplatePathAndFilename($template);
            $templateIdentifier = $templatePaths->getTemplateIdentifier();
            $parsedTemplate = null;
            try {
                $parsedTemplate = $renderingContext->getTemplateParser()->parse(
                    $templatePaths->getTemplateSource(),
                    $templateIdentifier,
                );
            } catch (\Exception $e) {
                // Extract line and character number from exception message
                if (
                    $e instanceof \TYPO3Fluid\Fluid\Core\Parser\Exception &&
                    preg_match('/^Fluid parse error in template (.*), line ([0-9]+) at character ([0-9]+)./', $e->getMessage(), $matches)
                ) {
                    $e = new LocationAwareParserException(
                        $e->getMessage(),
                        $e->getCode(),
                        $e,
                        new TemplateLocation($template, (int)$matches[2], (int)$matches[3]),
                    );
                }
                $errors[] = $e;
            }

            restore_error_handler();
            $results[$template] = new TemplateValidatorResult(
                $templateIdentifier,
                $template,
                $errors,
                $deprecations,
                $parsedTemplate,
            );
        }
        // Don't rely on order provided by file system to get predictable results across platforms
        ksort($results);
        return $results;
    }
}
