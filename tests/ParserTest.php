<?php

namespace Phpunit\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\genDiff;

class ParserTest extends TestCase
{
    public function testGenDiff(): void
    {
        $actual = "{\n- follow: false\n  host: hexlet.io\n- proxy: 123.234.53.22\n- timeout: 50\n+ timeout: 20\n+ verbose: true\n}";
        $this->assertEquals($actual, genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json'));
    }
}
