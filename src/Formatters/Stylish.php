<?php

namespace Differ\Formatters\Stylish;

function stylish(array $diffTree): string
{
    $diffTreeWithSymbols = formatDiffTreeWithSymbols($diffTree);
    $diffString = formatDiffTreeToString($diffTreeWithSymbols);
    return "{\n{$diffString}\n}";
}

function formatDiffTreeWithSymbols(array $diffTree): array
{
    $mappedItems = array_map(function ($data) {
        $status = $data['status'];
        $key = $data['key'];
        $value = $data['value'];

        switch ($status) {
            case 'unchanged':
                return [$key => formatValue($value)];

            case 'added':
                return ["+ {$key}" => formatValue($value)];

            case 'deleted':
                return ["- {$key}" => formatValue($value)];

            case 'changed':
                return [
                    "- {$key}" => formatValue($value['old']),
                    "+ {$key}" => formatValue($value['new'])
                ];

            case 'tree':
                $children = formatDiffTreeWithSymbols($value);
                return [$key => $children];

            default:
                return [];
        }
    }, $diffTree);

    return array_merge(...$mappedItems);
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
        default:
            return $value;
    }
}

function formatDiffTreeToString(array $tree, int $depth = 1): string
{
    $baseIndent = str_repeat(' ', $depth * 4);

    $lines = array_reduce(array_keys($tree), function ($result, $key) use ($tree, $depth, $baseIndent) {
        $value = $tree[$key];
        $hasSignPrefix = str_starts_with($key, '+') || str_starts_with($key, '-');
        $indent = ($hasSignPrefix) ? str_repeat(' ', $depth * 4 - 2) : $baseIndent;

        if (is_array($value)) {
            $result[] = "{$indent}{$key}: {";
            $children = formatDiffTreeToString($value, $depth + 1);
            $result[] = $children;
            $result[] = "{$baseIndent}}";
        } else {
            $result[] = "{$indent}{$key}: {$value}";
        }

        return $result;
    }, []);

    return implode("\n", $lines);
}
