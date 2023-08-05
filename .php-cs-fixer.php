<?php

$dirs = ['./src', './app', './tests'];

foreach ($dirs as $key => $dir) {
    if (!file_exists($dir)) {
        unset($dirs[$key]);
    }
}

$finder = PhpCsFixer\Finder::create()->in($dirs);
$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PSR12' => true,
    '@PHP73Migration' => true,
    '@PhpCsFixer' => true,
    'array_syntax' => ['syntax' => 'short'],
    'php_unit_test_class_requires_covers' => false,
    'php_unit_method_casing' => false,
    'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
    'phpdoc_types_order' => ['sort_algorithm' => 'alpha', 'null_adjustment' => 'always_last'],
    'concat_space' => ['spacing' => 'one'],
    'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
    'blank_line_before_statement' => [
        'statements' => [
            'do',
            'for',
            'foreach',
            'while',
            'if',
            'declare',
            'return',
            'try',
            'throw',
        ],
    ],
])->setFinder($finder);
