<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Formatters\format;

function genDiff(string $pathToFile1, string $pathToFile2, string $formatName = 'stylish'): string|false
{
    [$file1Content, $extension1] = getFileData($pathToFile1);
    [$file2Content, $extension2] = getFileData($pathToFile2);
    $parsedContent1 = parse($file1Content, $extension1);
    $parsedContent2 = parse($file2Content, $extension2);
    $diffTree = generateDiffTree($parsedContent1, $parsedContent2);
    return format($diffTree, $formatName);
}

function getFileData(string $filepath): mixed
{
    $fileContent = file_get_contents($filepath);
    $parts = explode('.', $filepath);
    $extension = end($parts);
    return [$fileContent, $extension];
}

function generateDiffTree(array $parsedContent1, array $parsedContent2): array
{
    $mergedArray = array_merge($parsedContent1, $parsedContent2);

    $arrayOfChanges = [];

    foreach ($mergedArray as $key => $value) {
        if (!array_key_exists($key, $parsedContent1)) {
            $arrayOfChanges[$key] = [
                'status' => 'added',
                'value' => $value
            ];
        } elseif (!array_key_exists($key, $parsedContent2)) {
            $arrayOfChanges[$key] = [
                'status' => 'deleted',
                'value' => $value
            ];
        } else {
            if ($parsedContent1[$key] === $parsedContent2[$key]) {
                $arrayOfChanges[$key] = [
                    'status' => 'unchanged',
                    'value' => $value,
                ];
            } else {
                if (is_array($parsedContent1[$key]) && is_array($parsedContent2[$key])) {
                    $arrayOfChanges[$key] = [
                        'status' => 'tree',
                        'value' => generateDiffTree($parsedContent1[$key], $parsedContent2[$key]),
                    ];
                } else {
                    $arrayOfChanges[$key] = [
                        'status' => 'changed',
                        'value' => [
                            'old' => $parsedContent1[$key],
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
