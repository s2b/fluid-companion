<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Fluid Companion',
    'description' => 'Companion extension for Fluid IDE integrations',
    'category' => 'misc',
    'author' => 'Simon Praetorius',
    'author_email' => 'simon@praetorius.me',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Praetorius\\FluidCompanion\\' => 'Classes/',
        ],
    ],
];
