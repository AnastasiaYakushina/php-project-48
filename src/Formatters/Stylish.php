<?php

namespace Differ\Formatters\Stylish;

use function Differ\Normalize\normalizeBoolNull;

function stylish(array $diffTree): string
{
    $diffTreeWithSymbols = formatDiffTreeWithSymbols($diffTree);
    $diffString = formatDiffTreeToString($diffTreeWithSymbols);
    return "{\n{$diffString}\n}";
}

function formatDiffTreeWithSymbols(array $diffTree): array
{
    $mappedItems = array_map(function ($data) {
        $key = $data['key'];
        $status = $data['status'];

        switch ($status) {
            case 'unchanged':
                return [$key => $data['value']];

            case 'added':
                return ["+ {$key}" => $data['value']];

            case 'deleted':
                return ["- {$key}" => $data['value']];

            case 'changed':
                return [
                    "- {$key}" => $data['value']['old'],
                    "+ {$key}" => $data['value']['new']
                ];

            case 'tree':
                $children = formatDiffTreeWithSymbols($data['value']);
                return [$key => $children];

            default:
                return [];
        }
    }, normalizeBoolNull($diffTree));

    return array_merge(...$mappedItems);
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
