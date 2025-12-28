<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\stylish;
use function Differ\Formatters\Plain\plain;
use function Differ\Formatters\Json\json;

function format(array $diffTree, string $formatName): string
{
    return match ($formatName) {
        'stylish' => stylish($diffTree),
        'plain' => plain($diffTree),
        'json' => json($diffTree),
        default => throw new \InvalidArgumentException("Incorrect format: '$formatName'"),
    };
}
