<?php
namespace Hexlet\Code\Tests;

use Exception;
use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Parsers\parser;
use function Hexlet\Code\Differ\diffToString;
class DifferTest extends TestCase
{
    private string $jsonFile1;
    private string $jsonFile2;
    private string $yamlFile1;
    private string $yamlFile2;

    public function setUp(): void
    {
        $this->jsonFile1 = 'tests/fixtures/file1.json';
        $this->jsonFile2 = 'tests/fixtures/file2.json';
        $this->yamlFile1 = 'tests/fixtures/file1.yml';
        $this->yamlFile2 = 'tests/fixtures/file2.yml';
    }

    /**
     * @throws Exception
     */
    public function testJsonFiles(): void
    {
        $excepted = [
            '- follow: false',
            '  host: hexlet.io',
            '- proxy: 123.234.53.22',
            '- timeout: 50',
            '+ timeout: 20',
            '+ verbose: true',
        ];

        $this->assertEquals(diffToString($excepted), parser($this->jsonFile1, $this->jsonFile2));
    }

    /**
     * @throws Exception
     */
    public function testYamlFiles(): void
    {
        $excepted = [
            '- follow: false',
            '  host: hexlet.io',
            '- proxy: 123.234.53.22',
            '- timeout: 50',
            '+ timeout: 20',
            '+ verbose: true',
        ];

        $this->assertEquals(diffToString($excepted), parser($this->yamlFile1, $this->yamlFile2));
    }

    public function testBorderlineCases(): void
    {
        $this->expectException(Exception::class);
        parser($this->jsonFile1, '/dir' . $this->jsonFile2);
    }
}
