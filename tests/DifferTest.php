<?php
namespace Hexlet\Code\Tests;

use Exception;
use PHPUnit\Framework\TestCase;

use function Hexlet\Code\Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $jsonFile1;
    private string $jsonFile2;
    private string $yamlFile1;
    private string $yamlFile2;
    private string $path = __DIR__ . "/fixtures/";
    private string $exceptedStylish;
    private string $exceptedPlain;
    private string $exceptedJson;
    private function getRealPath($name): string
    {
        return $this->path . $name;
    }
    public function setUp(): void
    {
        $this->jsonFile1 = $this->getRealPath('file1.json');
        $this->jsonFile2 = $this->getRealPath('file2.json');
        $this->yamlFile1 = $this->getRealPath('file1.yml');
        $this->yamlFile2 = $this->getRealPath('file2.yml');
        $stylishData = file_get_contents($this->getRealPath('stylishExcepted.txt'));
        $this->exceptedStylish = explode("\n\n\n", $stylishData)[0];
        $plainData = file_get_contents($this->getRealPath('plainExcepted.txt'));
        $this->exceptedPlain = explode("\n\n\n", $plainData)[0];
        $plainData = file_get_contents($this->getRealPath('jsonExcepted.txt'));
        $this->exceptedJson = explode("\n\n\n", $plainData)[0];
    }

    /**
     * @throws Exception
     */

    public function testStylishFormat(): void
    {
        $this->assertEquals($this->exceptedStylish, genDiff($this->jsonFile1, $this->jsonFile2));
        $this->assertEquals($this->exceptedStylish, genDiff($this->yamlFile1, $this->yamlFile2));
    }

    /**
     * @throws Exception
     */
    public function testPlainFormat(): void
    {
        $this->assertEquals($this->exceptedPlain, genDiff($this->jsonFile1, $this->jsonFile2, 'plain'));
        $this->assertEquals($this->exceptedPlain, genDiff($this->yamlFile1, $this->yamlFile2, 'plain'));
    }

    /**
     * @throws Exception
     */
    public function testJsonFormat(): void
    {
        $this->assertEquals($this->exceptedJson, genDiff($this->jsonFile1, $this->jsonFile2, 'json'));
        $this->assertEquals($this->exceptedJson, genDiff($this->yamlFile1, $this->yamlFile2, 'json'));
    }

    public function testBorderlineCases(): void
    {
        $this->expectException(Exception::class);
        genDiff($this->jsonFile1, '/dir' . $this->jsonFile2);
        genDiff($this->yamlFile1, $this->yamlFile2, 'otherFormat');
    }
}
