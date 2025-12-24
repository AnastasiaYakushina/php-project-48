<?php

namespace Differ\GenDiff;

use function Differ\Parser\parse;
use function Differ\Formatters\format;

function genDiff(string $pathToFile1, string $pathToFile2, string $formatName)
{
    $file1Data = parse($pathToFile1);
    $file2Data = parse($pathToFile2);
    $diffTree = generateDiffTree($file1Data, $file2Data);
    return format($diffTree, $formatName);
}

function generateDiffTree(array $file1Data, array $file2Data): array
{
    $mergedArray = array_merge($file1Data, $file2Data);

    $arrayOfChanges = [];
    foreach ($mergedArray as $key => $value) {
        if (!array_key_exists($key, $file1Data)) {
            $arrayOfChanges[$key] = [
                'status' => 'added',
                'value' => $value
            ];
        } elseif (!array_key_exists($key, $file2Data)) {
            $arrayOfChanges[$key] = [
                'status' => 'deleted',
                'value' => $value
            ];
        } else {
            if ($file1Data[$key] === $file2Data[$key]) {
                $arrayOfChanges[$key] = [
                    'status' => 'unchanged',
                    'value' => $value,
                ];
            } else {
                if (is_array($file1Data[$key]) && is_array($file2Data[$key])) {
                    $arrayOfChanges[$key] = [
                        'status' => 'tree',
                        'value' => generateDiffTree($file1Data[$key], $file2Data[$key]),
                    ];
                } else {
                    $arrayOfChanges[$key] = [
                        'status' => 'changed',
                        'value' => [
                            'old' => $file1Data[$key],
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
