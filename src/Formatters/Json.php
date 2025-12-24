<?php

namespace Differ\Formatters\Json;

function json(array $diffTree): string|false
{
    return json_encode($diffTree);
}
