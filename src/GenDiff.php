<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Formatters\format;

function genDiff(string $pathToFile1, string $pathToFile2, string $formatName = 'stylish'): string|false
{
    $file1Content = parse($pathToFile1);
    $file2Content = parse($pathToFile2);
    $diffTree = generateDiffTree($file1Content, $file2Content);
    return format($diffTree, $formatName);
}

function generateDiffTree(array $file1Content, array $file2Content): array
{
    $mergedArray = array_merge($file1Content, $file2Content);

    $arrayOfChanges = [];

    foreach ($mergedArray as $key => $value) {
        if (!array_key_exists($key, $file1Content)) {
            $arrayOfChanges[$key] = [
                'status' => 'added',
                'value' => $value
            ];
        } elseif (!array_key_exists($key, $file2Content)) {
            $arrayOfChanges[$key] = [
                'status' => 'deleted',
                'value' => $value
            ];
        } else {
            if ($file1Content[$key] === $file2Content[$key]) {
                $arrayOfChanges[$key] = [
                    'status' => 'unchanged',
                    'value' => $value,
                ];
            } else {
                if (is_array($file1Content[$key]) && is_array($file2Content[$key])) {
                    $arrayOfChanges[$key] = [
                        'status' => 'tree',
                        'value' => generateDiffTree($file1Content[$key], $file2Content[$key]),
                    ];
                } else {
                    $arrayOfChanges[$key] = [
                        'status' => 'changed',
                        'value' => [
                            'old' => $file1Content[$key],
                            'new' => $value
                        ]
                    ];
                }
            }
        }
    }

    $collection = collect($arrayOfChanges);
    $sortedCollection = $collection->sortKeys();
    return $sortedCollection->all();
}
