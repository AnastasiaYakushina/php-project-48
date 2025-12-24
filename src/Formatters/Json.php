<?php

namespace Differ\Formatters\Json;

function json(array $diffTree): string
{
    return json_encode($diffTree);
}
