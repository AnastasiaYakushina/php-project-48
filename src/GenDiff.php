<?php

namespace Differ\GenDiff;

use function Differ\Parser\parse;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $file1Data = parse($pathToFile1);
    $file2Data = parse($pathToFile2);
    $mergedArray = array_merge($file1Data, $file2Data);

    $arrayOfChanges = [];
    foreach ($mergedArray as $key => $value) {
        if (!array_key_exists($key, $file1Data)) {
            $arrayOfChanges[$key] = ['added', $value];
        } elseif (!array_key_exists($key, $file2Data)) {
            $arrayOfChanges[$key] = ['deleted', $value];
        } else {
            if ($file1Data[$key] === $file2Data[$key]) {
                $arrayOfChanges[$key] = ['unchanged', $value];
            } else {
                $arrayOfChanges[$key] = ['changed', $file1Data[$key], $value];
            }
        }
    }

    $collection = collect($arrayOfChanges);
    $sortedCollection = $collection->sortKeys();
    $sortedArrayOfChanges = $sortedCollection->all();

    $stringsArray = [];
    foreach ($sortedArrayOfChanges as $key => $value) {
        if ($value[0] === 'unchanged') {
            $stringsArray[] = "  $key: $value[1]";
        } elseif ($value[0] === 'added') {
            $stringsArray[] = "+ $key: $value[1]";
        } elseif ($value[0] === 'deleted') {
            $stringsArray[] = "- $key: $value[1]";
        } elseif ($value[0] === 'changed') {
            $stringsArray[] = "- $key: $value[1]";
            $stringsArray[] = "+ $key: $value[2]";
        }
    }

    $result = implode("\n", $stringsArray);
    // var_dump(("{\n$result\n}"));
    return "{\n$result\n}";
}
