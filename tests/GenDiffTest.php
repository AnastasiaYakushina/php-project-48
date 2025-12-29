<?php

namespace Phpunit\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    #[DataProvider('genDiffProvider')]
    public function testGenDiff(string $expectedFile, string $extension, ?string $formatName = null): void
    {
        $expectedFilePath = $this->getFixtureFullPath($expectedFile);
        $filePath1 = $this->getFixtureFullPath("file1.{$extension}");
        $filePath2 = $this->getFixtureFullPath("file2.{$extension}");

        if (isset($formatName)) {
            $actual = genDiff($filePath1, $filePath2, $formatName);
        } else {
            $actual = genDiff($filePath1, $filePath2);
        }

        $this->assertStringEqualsFile($expectedFilePath, $actual);
    }

    public static function genDiffProvider(): array
    {
        return [
            'JSON files, format is not passed' => ['expectedStylish.txt', 'json'],

            'YAML files, format is not passed' => ['expectedStylish.txt', 'yml'],

            'JSON files with stylish format' => ['expectedStylish.txt', 'json', 'stylish'],

            'YAML files with stylish format' => ['expectedStylish.txt', 'yml', 'stylish'],

            'JSON files with plain format' => ['expectedPlain.txt', 'json', 'plain'],

            'YAML files with plain format' => ['expectedPlain.txt', 'yml', 'plain'],

            'JSON files with JSON format' => ['expected.json', 'json', 'json'],

            'YAML files with JSON format' => ['expected.json', 'yml', 'json']
        ];
    }

    public function getFixtureFullPath(string $fixtureName): string
    {
        $parts = [__DIR__, '/fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }
}
