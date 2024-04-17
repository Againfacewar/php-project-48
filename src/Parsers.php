<?php

namespace Hexlet\Code\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Functional\map;
use function Hexlet\Code\Differ\genDiff;

/**
 * @throws \Exception
 */
function parser(string $file1, string $file2, string $format = 'stylish'): string
{
    $filePath1 = realpath($file1);
    $filePath2 = realpath($file2);
    if (!$filePath1 || !$filePath2) {
        throw new \Exception('Один или оба переданных файла не найдены. Убедитесь, что указанные пути корректны!');
    }

    [$encodedFile1, $encodedFile2] = map([$filePath1, $filePath2], function ($path) {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $encodedFile = file_get_contents($path);

        return match ($ext) {
            'yaml', 'yml' => Yaml::parse($encodedFile),
            'json' => json_decode($encodedFile, true),
            default => throw new \Exception('Данный формат файла не поддерживается!'),
        };
    });

    return genDiff($encodedFile1, $encodedFile2, $format);
}
