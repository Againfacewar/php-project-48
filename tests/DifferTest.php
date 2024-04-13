<?php
namespace Hexlet\Code\Tests;

use Exception;
use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\genDiff;
use function Hexlet\Code\Differ\diffToString;
class DifferTest extends TestCase
{
    private string $file1;
    private string $file2;

    public function setUp(): void
    {
        $this->file1 = 'tests/fixtures/file1.json';
        $this->file2 = 'tests/fixtures/file2.json';

    }

    /**
     * @throws Exception
     */
    public function testMainFlow(): void
    {
        $excepted = [
            '- follow: false',
            '  host: hexlet.io',
            '- proxy: 123.234.53.22',
            '- timeout: 50',
            '+ timeout: 20',
            '+ verbose: true',
        ];

        $this->assertEquals(diffToString($excepted), genDiff($this->file1, $this->file2));
    }

    public function testBorderlineCases(): void
    {
        $this->expectException(Exception::class);
        genDiff($this->file1, '/dir' . $this->file2);
    }
}
