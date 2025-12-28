<?php

namespace Differ\Differ;

use function Differ\Parser\parse;
use function Differ\Formatters\format;

function genDiff(string $filePath1, string $filePath2, string $formatName = 'stylish'): string
{
    [$file1Content, $extension1] = getFileData($filePath1);
    [$file2Content, $extension2] = getFileData($filePath2);
    $content1 = parse($file1Content, $extension1);
    $content2 = parse($file2Content, $extension2);
    $diffTree = generateDiffTree($content1, $content2);
    return format($diffTree, $formatName);
}

function getFileData(string $filepath): array
{
    $fileContent = file_get_contents($filepath);
    $extension = pathinfo($filepath)['extension'];
    return [$fileContent, $extension];
}

function generateDiffTree(array $content1, array $content2): array
{
    $keys = array_unique(array_merge(array_keys($content1), array_keys($content2)));
    $collection = collect($keys);
    $sortedKeys = $collection->sort()->all();

    return array_map(function ($key) use ($content1, $content2) {
        $keyInContent1 = array_key_exists($key, $content1);
        $keyInContent2 = array_key_exists($key, $content2);

        if (!$keyInContent1) {
            return [
                'key' => $key,
                'status' => 'added',
                'value' => $content2[$key]
            ];
        }

        if (!$keyInContent2) {
            return [
                'key' => $key,
                'status' => 'deleted',
                'value' => $content1[$key]
            ];
        }

        if (is_array($content1[$key]) && is_array($content2[$key])) {
            return [
                'key' => $key,
                'status' => 'tree',
                'value' => generateDiffTree($content1[$key], $content2[$key]),
            ];
        }

        if ($content1[$key] === $content2[$key]) {
            return [
                'key' => $key,
                'status' => 'unchanged',
                'value' => $content1[$key],
            ];
        }

        return [
            'key' => $key,
            'status' => 'changed',
            'value' => [
                'old' => $content1[$key],
                'new' => $content2[$key]
            ]
        ];
    }, $sortedKeys);
}
