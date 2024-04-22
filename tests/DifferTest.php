<?php

namespace Hexlet\Code\Tests;

use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $path = __DIR__ . "/fixtures/";

    private function getRealPath($name): string
    {
        return $this->path . $name;
    }

    public static function dataProvider(): array
    {
        return [
            ["stylishExcepted.txt", "file1.json", "file2.json"],
            ["stylishExcepted.txt", "file1.yml", "file2.yml"],
            ["plainExcepted.txt", "file1.json", "file2.json", "plain"],
            ["plainExcepted.txt", "file1.yml", "file2.yml", "plain"],
            ["jsonExcepted.txt", "file1.json", "file2.json", "json"],
            ["jsonExcepted.txt", "file1.yml", "file2.yml", "json"],
        ];
    }

    /**
     * @throws Exception
     */
    #[DataProvider('dataProvider')]
    public function testMainFlow(string $excepted, string $file1, string $file2, string $format = 'stylish'): void
    {
        $data = file_get_contents($this->getRealPath($excepted));
        $exceptedData = explode("\n\n\n", $data)[0];
        $this->assertEquals($exceptedData, genDiff($this->getRealPath($file1), $this->getRealPath($file2), $format));
    }
    public function testBorderlineCases(): void
    {
        $this->expectException(Exception::class);
        genDiff($this->getRealPath("file1.json"), '/dir' . $this->getRealPath("file2.json"));
        genDiff($this->getRealPath("file1.json"), $this->getRealPath("file2.json"), 'otherFormat');
    }
}
