<?php

namespace App;

class FileReader
{
    public $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function parse(string $filepath): void
    {
        $fileContent = file_get_contents($filepath);
        $data = json_decode($fileContent);
        foreach ($data as $key => $value) {
            var_dump("Key: {$key}, value: {$value}");
        }
    }
}
