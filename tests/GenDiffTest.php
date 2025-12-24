<?php

namespace Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\GenDiff\genDiff;

class GenDiffTest extends TestCase
{
    public function testGenDiffStylish(): void
    {
        $expected = file_get_contents('tests/fixtures/expectedStylish.txt');
        $this->assertEquals($expected, genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json'));
        $this->assertEquals($expected, genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yml'));
    }

    public function testGenDiffPlain(): void
    {
        $expected = file_get_contents('tests/fixtures/expectedPlain.txt');
        $this->assertEquals($expected, genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json', 'plain'));
        $this->assertEquals($expected, genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yml', 'plain'));
    }

    public function testGenDiffJson(): void
    {
        $expected = file_get_contents('tests/fixtures/expected.json');
        $this->assertJsonStringEqualsJsonString($expected, genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json', 'json'));
        $this->assertJsonStringEqualsJsonString($expected, genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yml', 'json'));
    }
}
