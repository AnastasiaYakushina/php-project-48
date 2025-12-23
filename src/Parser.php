<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $filepath): array
{
    $extension = getExtension($filepath);
    $fileContent = file_get_contents($filepath);
    if ($extension === 'json') {
        return convertBooleanNullToString(json_decode($fileContent, true));
    }
    if ($extension === 'yml' || $extension === 'yaml') {
        return convertBooleanNullToString(Yaml::parse($fileContent));
    }
}

function getExtension(string $str): string
{
    $parts = explode('.', $str);
    return end($parts);
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
