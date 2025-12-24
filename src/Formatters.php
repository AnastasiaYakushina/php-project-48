<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Json\json;

function format(array $diffTree, string $formatName): string
{
    if ($formatName === 'plain') {
        return plain($diffTree);
    }
    if ($formatName === 'json') {
        return json($diffTree);
    }
    return stylish($diffTree);
}
