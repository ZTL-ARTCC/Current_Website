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
    'braces_position' => [
        'classes_opening_brace' => 'same_line',
        'control_structures_opening_brace' => 'same_line',
        'functions_opening_brace' => 'same_line',
        'allow_single_line_anonymous_functions' => true,
    ]
])->setFinder($finder);

return $config;
