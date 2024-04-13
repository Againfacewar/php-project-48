<?php

namespace Hexlet\Code\Differ;

use function Functional\map;
use function Functional\reduce_left;

/**
 * @throws \Exception
 */
function genDiff(string $file1, string $file2): string
{
    $filePath1 = realpath($file1);
    $filePath2 = realpath($file2);
    if (!$filePath1 || !$filePath2) {
        throw new \Exception('Один или оба переданных файла не найдены. Убедитесь, что указанные пути корректны!');
    }

    $encodedFile1 = json_decode(file_get_contents($filePath1), true);
    $encodedFile2 = json_decode(file_get_contents($filePath2), true);

    return diffToString(compareFiles($encodedFile1, $encodedFile2));
}

function compareFiles(array $file1, array $file2): array
{
    $result = reduce_left($file2, function ($item, $key, $map, $acc) use ($file1) {
        $item = valueToString($item);

        if (array_key_exists($key, $file1)) {
            $acc[] = $item === $file1[$key] ? ['key' => $key, 'value' => "  $key: $item"]
                : ['key' => $key, 'value' => "+ $key: $item"];
        } else {
            $acc[] = ['key' => $key, 'value' => "+ $key: $item"];
        }

        return $acc;
    }, reduce_left($file1, function ($item, $key, $map, $acc) use ($file2) {
        $item = valueToString($item);

        if (array_key_exists($key, $file2)) {
            $acc[] = $item === $file2[$key] ? ['key' => $key, 'value' => "  $key: $item"]
                : ['key' => $key, 'value' => "- $key: $item"];
        } else {
            $acc[] = ['key' => $key, 'value' => "- $key: $item"];
        }

        return $acc;
    }, []));

    usort($result, fn($left, $right) => strcmp($left['key'], $right['key']));

    return array_unique(map($result, fn($item) => $item['value']));
}

function diffToString(array $map): string
{
    print_r($map);
    return "{" . PHP_EOL . "  " . implode("\n  ", $map) . PHP_EOL . "}" . PHP_EOL;
}

function valueToString(mixed $val): string
{
    $newVal = $val;
    if (is_bool($val)) {
        $newVal = $val ? 'true' : 'false';
    }

    return $newVal;
}
