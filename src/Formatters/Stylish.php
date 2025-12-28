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

        return match ($status) {
            'unchanged' => [$key => formatValue($value)],
            'added' => ["+ {$key}" => formatValue($value)],
            'deleted' => ["- {$key}" => formatValue($value)],
            'changed' => [
                "- {$key}" => formatValue($value['old']),
                "+ {$key}" => formatValue($value['new'])
            ],
            'tree' => [
                $key => formatDiffTreeWithSymbols($value)
            ],
            default => [],
        };
    }, $diffTree);

    return array_merge(...$mappedItems);
}

function formatValue(mixed $value): mixed
{
    return match (true) {
        $value === true => 'true',
        $value === false => 'false',
        $value === null => 'null',
        default => $value,
    };
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
