# Fluid Companion

This extension for TYPO3 CMS backports Fluid features introduced with TYPO3 14 and
Fluid 5, which enable a better integration with code editors and IDEs. The extension
is compatible with TYPO3 12 and 13.

## Installation

```sh
composer req --dev praetorius/fluid-companion
```

## Available Console Commands

* `vendor/bin/typo3 fluid:namespaces`: Lists registered global Fluid namespaces
* `vendor/bin/typo3 fluid:analyze --stdin --json`: Reads a template string from STDIN
  and checks its correctness (parser errors and deprecations). Note that this command
  is only partially backported, only the described combination of arguments works.
