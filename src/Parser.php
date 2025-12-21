<?php

namespace Differ\Parser;

use Symfony\Component\Yaml\Yaml;

function parse(string $filepath): array
{
    $extension = getExtension($filepath);
    $fileContent = file_get_contents($filepath);
    if ($extension === 'json') {
        return convertBooleanValuesToString(json_decode($fileContent, true));
    }
    if ($extension === 'yml' || $extension === 'yaml') {
        return convertBooleanValuesToString(Yaml::parse($fileContent));
    }
}

function getExtension(string $str): string
{
    $parts = explode('.', $str);
    return end($parts);
}

function convertBooleanValuesToString(array $array): array
{
    $result = [];
    foreach ($array as $key => $value) {
        $newValue = is_bool($value) ? ($value ? 'true' : 'false') : $value;
        $result[$key] = $newValue;
    }
    return $result;
}
