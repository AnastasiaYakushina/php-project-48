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
    $diffLines = array_map(function ($data) use ($path) {
        $key = $data['key'];
        $currentPath = $path === [] ? [$key] : [...$path, $key];
        $currentKey = implode('.', $currentPath);
        $status = $data['status'];

        switch ($status) {
            case 'added':
                $value = getValue($data['value']);
                return ["Property '{$currentKey}' was added with value: {$value}"];

            case 'deleted':
                return ["Property '{$currentKey}' was removed"];

            case 'changed':
                $oldValue = getValue($data['value']['old']);
                $newValue = getValue($data['value']['new']);
                return ["Property '{$currentKey}' was updated. From {$oldValue} to {$newValue}"];

            case 'tree':
                return formatDiffTreeToStrings($data['value'], $currentPath);

            default:
                return [];
        }
    }, normalizeBoolNull($diffTree));

    return !($diffLines === []) ? array_merge(...$diffLines) : [];
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
