<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(mixed $fileContent, string $extension): array
{
    if ($extension === 'json') {
        return json_decode($fileContent, true);
    }
    if ($extension === 'yml' || $extension === 'yaml') {
        return Yaml::parse($fileContent);
    }
    return [];
}
