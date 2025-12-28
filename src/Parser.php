<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(mixed $fileContent, string $extension): array
{
    return match ($extension) {
        'json' => json_decode($fileContent, true),
        'yml', 'yaml' => Yaml::parse($fileContent),
        default => throw new \InvalidArgumentException("Unsupported file extension: '$extension'"),
    };
}
