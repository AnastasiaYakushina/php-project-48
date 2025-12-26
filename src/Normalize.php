<?php

namespace Differ\Normalize;

function normalizeBoolNull(array $array): array
{
    $result = [];

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result[$key] = normalizeBoolNull($value);
        } else {
            if ($value === true) {
                $result[$key] = 'true';
            } elseif ($value === false) {
                $result[$key] = 'false';
            } elseif ($value === null) {
                $result[$key] = 'null';
            } else {
                $result[$key] = $value;
            }
        }
    }

    return $result;
}
