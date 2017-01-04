<?php

$finder = PhpCsFixer\Finder::create()
    ->in('src')
    ->in('spec')
    ->in('tests');

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        'concat_space' => true,
    ])
    ->setFinder($finder);
