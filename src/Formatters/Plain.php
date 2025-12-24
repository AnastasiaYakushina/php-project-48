<?php

namespace Differ\Formatters\Plain;

function plain(array $diffTree): string
{
    $diffString = formatDiffTreeToStrings($diffTree);
    return implode("\n", $diffString);
}

function formatDiffTreeToStrings(array $diffTree, array $path = []): array
{
    $diffTreeWithSymbols = [];

    foreach ($diffTree as $key => $data) {
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
