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

        return match ($status) {
            'added' => ["Property '{$currentKey}' was added with value: " . formatValue($value)],
            'deleted' => ["Property '{$currentKey}' was removed"],
            'changed' => [
                "Property '{$currentKey}' was updated. From " .
                formatValue($value['old']) . " to " . formatValue($value['new'])
            ],
            'tree' => formatDiffTreeToStrings($value, $currentPath),
            default => [],
        };
    }, $diffTree);

    return ($lines !== []) ? array_merge(...$lines) : [];
}


function formatValue(mixed $value): mixed
{
    return match (true) {
        $value === true => 'true',
        $value === false => 'false',
        $value === null => 'null',
        is_array($value) => '[complex value]',
        is_string($value) => "'{$value}'",
        default => $value,
    };
}
