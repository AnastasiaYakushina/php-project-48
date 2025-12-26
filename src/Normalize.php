<?php

namespace Differ\Normalize;

function normalizeBoolNull(array $array): array
{
    return array_map(function ($value) {
        if (is_array($value)) {
            return normalizeBoolNull($value);
        }

        if ($value === true) {
            return 'true';
        } elseif ($value === false) {
            return 'false';
        } elseif ($value === null) {
            return 'null';
        } else {
            return $value;
        }
    }, $array);
}
