<?php

namespace Phpunit\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    #[DataProvider('genDiffProvider')]
    public function testGenDiff(string $expectedFile, array $testData): void
    {
        $expectedFilePath = $this->getFixtureFullPath($expectedFile);
        $filePath1 = $this->getFixtureFullPath($testData['filename1']);
        $filePath2 = $this->getFixtureFullPath($testData['filename2']);

        if (isset($testData['formatName'])) {
            $actual = genDiff($filePath1, $filePath2, $testData['formatName']);
        } else {
            $actual = genDiff($filePath1, $filePath2);
        }

        $this->assertStringEqualsFile($expectedFilePath, $actual);
    }

    public static function genDiffProvider(): array
    {
        return [
            'JSON files, format is not passed' => ['expectedStylish.txt',
            ['filename1' => 'file1.json', 'filename2' => 'file2.json']],

            'YAML files, format is not passed' => ['expectedStylish.txt',
            ['filename1' => 'file1.yml', 'filename2' => 'file2.yml']],

            'JSON files with stylish format' => ['expectedStylish.txt',
            ['filename1' => 'file1.json', 'filename2' => 'file2.json', 'formatName' => 'stylish']],

            'YAML files with stylish format' => ['expectedStylish.txt',
            ['filename1' => 'file1.yml', 'filename2' => 'file2.yml', 'formatNewton' => 'stylish']],

            'JSON files with plain format' => ['expectedPlain.txt',
            ['filename1' => 'file1.json', 'filename2' => 'file2.json', 'formatName' => 'plain']],

            'YAML files with plain format' => ['expectedPlain.txt',
            ['filename1' => 'file1.yml', 'filename2' => 'file2.yml', 'formatName' => 'plain']],

            'JSON files with JSON format' => ['expected.json',
            ['filename1' => 'file1.json', 'filename2' => 'file2.json', 'formatName' => 'json']],

            'YAML files with JSON format' => ['expected.json',
            ['filename1' => 'file1.yml', 'filename2' => 'file2.yml', 'formatName' => 'json']]
        ];
    }

    public function getFixtureFullPath(string $fixtureName): string
    {
        $parts = [__DIR__, '/fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }
}
