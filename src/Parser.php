<?php

namespace Parser;

function parse(string $filepath): void
{
    $fileContent = file_get_contents($filepath);
    $data = json_decode($fileContent);
    foreach ($data as $key => $value) {
        var_dump("Key: {$key}, value: {$value}");
    }
}
