<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Json\json;

function format(array $diffTree, string $formatter): string
{
    if ($formatter === 'stylish') {
        return stylish($diffTree);
    }
    if ($formatter === 'plain') {
        return plain($diffTree);
    }
    if ($formatter === 'json') {
        return json($diffTree);
    }
}
