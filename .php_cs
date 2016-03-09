<?php

use Symfony\CS\Config\Config;
use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\FixerInterface;

$finder = DefaultFinder::create()->in([__DIR__ . '/src']);

$fixers = [
    '-spaces_cast',
    '-ternary_spaces',
    '-empty_return',
    '-phpdoc_short_description',
    'concat_with_spaces',
    'short_array_syntax',
    'ordered_use',
    'phpdoc_order',
];

return Config::create()->level(FixerInterface::SYMFONY_LEVEL)->fixers($fixers)->finder($finder);
