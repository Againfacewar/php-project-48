<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Functional\map;

/**
 * @throws \Exception
 */
function parser(string $file1, string $file2): array
{
    $filePath1 = realpath($file1);
    $filePath2 = realpath($file2);
    if (!file_exists($filePath1) || !file_exists($filePath2)) {
        throw new \Exception('One or both of the transferred files were not found.
         Make sure that the specified paths are correct!');
    }

    [$encodedFile1, $encodedFile2] = map([$filePath1, $filePath2], function ($path) {
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $encodedFile = file_get_contents($path);

        return match ($ext) {
            'yaml', 'yml' => Yaml::parse((string) $encodedFile),
            'json' => json_decode((string) $encodedFile, true),
            default => throw new \Exception('Данный формат файла не поддерживается!'),
        };
    });

    return [$encodedFile1, $encodedFile2];
}
