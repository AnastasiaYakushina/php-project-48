<?php

namespace Differ\Formatters\Plain;

use function Differ\Normalize\normalizeBoolNull;

function plain(array $diffTree): string
{
    $diffString = formatDiffTreeToStrings($diffTree);
    return implode("\n", $diffString);
}

function formatDiffTreeToStrings(array $diffTree, array $path = []): array
{
    $diffTreeWithSymbols = [];

    foreach (normalizeBoolNull($diffTree) as $data) {
        $key = $data['key'];
        $currentPath = $path === [] ? [$key] : [...$path, $key];
        $currentKey = implode('.', $currentPath);
        $status = $data['status'];
        if ($status === 'added') {
            $value = getValue($data['value']);
            $diffTreeWithSymbols[] = "Property '{$currentKey}' was added with value: {$value}";
        } elseif ($status === 'deleted') {
            $diffTreeWithSymbols[] = "Property '{$currentKey}' was removed";
        } elseif ($status === 'changed') {
            $oldValue = getValue($data['value']['old']);
            $newValue = getValue($data['value']['new']);
            $diffTreeWithSymbols[] = "Property '{$currentKey}' was updated. From {$oldValue} to {$newValue}";
        } elseif ($status === 'tree') {
            $children = formatDiffTreeToStrings($data['value'], $currentPath);
            $diffTreeWithSymbols = [...$diffTreeWithSymbols, ...$children];
        }
    }

    return $diffTreeWithSymbols;
}

function getValue(mixed $value): mixed
{
    $booleanNullValues = ['true', 'false', 'null'];

    if (is_array($value)) {
        return '[complex value]';
    } elseif (is_string($value) && !in_array($value, $booleanNullValues, true)) {
        return "'{$value}'";
    }
    return $value;
}

// function convertBooleanNullToString(array $array): array
// {
//     $result = [];

//     foreach ($array as $key => $value) {
//         if (is_array($value)) {
//             $result[$key] = convertBooleanNullToString($value);
//         } else {
//             if ($value === true) {
//                 $result[$key] = 'true';
//             } elseif ($value === false) {
//                 $result[$key] = 'false';
//             } elseif ($value === null) {
//                 $result[$key] = 'null';
//             } else {
//                 $result[$key] = $value;
//             }
//         }
//     }

//     return $result;
// }
