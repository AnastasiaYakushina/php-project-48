<?php

namespace Differ\Formatters\Plain;

function plain(array $diffTree): string
{
    $diffString = formatDiffTreeToStrings($diffTree);
    return implode("\n", $diffString);
}

function formatDiffTreeToStrings(array $diffTree, array $path = []): array
{
    $lines = array_map(function ($data) use ($path) {
        $status = $data['status'];
        $key = $data['key'];
        $value = $data['value'];

        $currentPath = $path === [] ? [$key] : [...$path, $key];
        $currentKey = implode('.', $currentPath);

        switch ($status) {
            case 'added':
                $value = formatValue($value);
                return ["Property '{$currentKey}' was added with value: {$value}"];

            case 'deleted':
                return ["Property '{$currentKey}' was removed"];

            case 'changed':
                $oldValue = formatValue($value['old']);
                $newValue = formatValue($value['new']);
                return ["Property '{$currentKey}' was updated. From {$oldValue} to {$newValue}"];

            case 'tree':
                return formatDiffTreeToStrings($value, $currentPath);

            default:
                return [];
        }
    }, $diffTree);

    return !($lines === []) ? array_merge(...$lines) : [];
}


function formatValue(mixed $value): mixed
{
    switch (true) {
        case $value === true:
            return 'true';
        case $value === false:
            return 'false';
        case $value === null:
            return 'null';
        case is_array($value):
            return '[complex value]';
        case is_string($value):
            return "'{$value}'";
        default:
            return $value;
    }
}
