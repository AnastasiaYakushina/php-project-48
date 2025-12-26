<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(mixed $fileContent, string $extension): array
{
    switch ($extension) {
        case 'json':
            return json_decode($fileContent, true);

        case 'yml':
        case 'yaml':
            return Yaml::parse($fileContent);

        default:
            return [];
    }
}
