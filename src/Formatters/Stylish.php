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
    $diffTreeWithSymbols = [];
    foreach ($diffTree as $key => $data) {
        $status = $data['status'];
        if ($status === 'unchanged') {
            $diffTreeWithSymbols["{$key}"] = $data['value'];
        } elseif ($status === 'added') {
            $diffTreeWithSymbols["+ {$key}"] = $data['value'];
        } elseif ($status === 'deleted') {
            $diffTreeWithSymbols["- {$key}"] = $data['value'];
        } elseif ($status === 'changed') {
            $diffTreeWithSymbols["- {$key}"] = $data['value']['old'];
            $diffTreeWithSymbols["+ {$key}"] = $data['value']['new'];
        } elseif ($status === 'tree') {
            $children = formatDiffTreeWithSymbols($data['value']);
            $diffTreeWithSymbols["{$key}"] = $children;
        }
    }
    return $diffTreeWithSymbols;
}

function formatDiffTreeToString(array $tree, int $depth = 1): string
{
    $lines = [];
    $baseIndent = str_repeat(' ', $depth * 4);
    foreach ($tree as $key => $value) {
        $hasSignPrefix = str_starts_with($key, '+') || str_starts_with($key, '-');
        $indent = ($hasSignPrefix) ? str_repeat(' ', $depth * 4 - 2) : $baseIndent;
        if (is_array($value)) {
            $lines[] = "{$indent}{$key}: {";
            $children = formatDiffTreeToString($value, $depth + 1);
            $lines[] = $children;
            $lines[] = "{$baseIndent}}";
        } else {
            $lines[] = "{$indent}{$key}: {$value}";
        }
    }

    return implode("\n", $lines);
}
