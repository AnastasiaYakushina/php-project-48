<?php

namespace Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\GenDiff\genDiff;

class ParserTest extends TestCase
{
    public function testGenDiff(): void
    {
        $actual = file_get_contents('tests/fixtures/expected.txt');
        $this->assertEquals($actual, genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json'));
        $this->assertEquals($actual, genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yml'));
    }
}
