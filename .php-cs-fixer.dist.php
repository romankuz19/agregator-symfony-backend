<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('node_modules')
    ->exclude('public')
    ->exclude('tests')
    ->name('*.php');

return (new Config())
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'ordered_imports' => true,
        'declare_strict_types' => true,
        'concat_space' => ['spacing' => 'one'],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);