<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $filepath): array
{
    $fileContent = file_get_contents($filepath);
    $parts = explode('.', $filepath);
    $extension = end($parts);
    $parsedContent = decodeFileContent($fileContent, $extension);
    return convertBooleanNullToString($parsedContent);
}

function decodeFileContent(mixed $fileContent, string $extension): array
{
    if ($extension === 'json') {
        return json_decode($fileContent, true);
    }
    if ($extension === 'yml' || $extension === 'yaml') {
        return Yaml::parse($fileContent);
    }
    return [];
}

function convertBooleanNullToString(array $array): array
{
    $result = [];

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result[$key] = convertBooleanNullToString($value);
        } else {
            if ($value === true) {
                $result[$key] = 'true';
            } elseif ($value === false) {
                $result[$key] = 'false';
            } elseif ($value === null) {
                $result[$key] = 'null';
            } else {
                $result[$key] = $value;
            }
        }
    }

    return $result;
}
