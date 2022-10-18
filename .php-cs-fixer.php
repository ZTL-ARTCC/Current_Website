<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('vendor')
    ->notPath('bootstrap')
    ->notPath('storage')
    ->notPath('public/ckeditor')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php');

$config = new PhpCsFixer\Config();

$config->setRules([
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'no_unused_imports' => true,
    'braces' => [
        'position_after_functions_and_oop_constructs' => 'same',
        'allow_single_line_closure' => true,
    ],
])->setFinder($finder);

return $config;
