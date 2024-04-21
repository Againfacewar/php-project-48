<?php

namespace Differ\Differ;

use function Functional\reduce_left;
use function Differ\Formatters\selectFormatter;
use function Differ\Parsers\parse;
use function Functional\sort;

/**
 * @throws \Exception
 */
function genDiff(string $firstPath, string $secondPath, string $format = 'stylish'): string
{
    $filePath1 = realpath($firstPath);
    $filePath2 = realpath($secondPath);
    if (!file_exists((string) $filePath1) || !file_exists((string) $filePath2)) {
        throw new \Exception('One or both of the transferred files were not found.
         Make sure that the specified paths are correct!');
    }

    $firstFile = file_get_contents((string) $filePath1);
    $firstFileExt = pathinfo((string) $filePath1, PATHINFO_EXTENSION);
    $secondFile = file_get_contents((string) $filePath2);
    $secondFileExt = pathinfo((string) $filePath1, PATHINFO_EXTENSION);
    $encodedFirstFile = parse($firstFile, $firstFileExt);
    $encodedSecondFile = parse($secondFile, $secondFileExt);
    $differ = compareFiles($encodedFirstFile, $encodedSecondFile, 1);

    return selectFormatter($differ, $format);
}

function compareFiles(array $firstFile, array $secondFile, int $depth): array
{
    $keys = [...array_unique(array_merge(array_keys($firstFile), array_keys($secondFile)))];
    $sortedKeys = sort($keys, fn($left, $right) => strcmp($left, $right));

    return reduce_left(
        $sortedKeys,
        function ($item, $key, $map, $acc) use ($firstFile, $secondFile, $depth) {

            if (array_key_exists($item, $firstFile) && array_key_exists($item, $secondFile)) {
                if (is_array($firstFile[$item]) && is_array($secondFile[$item])) {
                    $newEntry =
                    ['type' => 'internal', "key" => $item,
                        'children' => compareFiles($firstFile[$item], $secondFile[$item], $depth + 1)] ;
                } elseif ($firstFile[$item] === $secondFile[$item]) {
                    $newEntry = ['type' => 'unchanged', 'key' => $item, 'value' => $firstFile[$item]];
                } else {
                    $newEntry =
                    ['type' => 'updated', 'key' => $item,
                        'value' => ['first' => $firstFile[$item], 'second' => $secondFile[$item]]];
                }
            } elseif (array_key_exists($item, $firstFile)) {
                $newEntry = ['type' => 'removed', 'key' => $item, 'value' => $firstFile[$item]];
            } elseif (array_key_exists($item, $secondFile)) {
                $newEntry = ['type' => 'added', 'key' => $item, 'value' => $secondFile[$item]];
            }
            return isset($newEntry) ? array_merge($acc, [$newEntry]) : $acc;
        },
        []
    );
}
