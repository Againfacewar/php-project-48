<?php

namespace Differ\Differ;

use function Functional\reduce_left;
use function Differ\Formatters\selectFormatter;
use function Differ\Parsers\parser;
use function Functional\sort;

/**
 * @throws \Exception
 */
function genDiff(string $firstPath, string $secondPath, string $format = 'stylish'): string
{
    [$firstFile, $secondFile] = parser($firstPath, $secondPath);
    $differ = compareFiles($firstFile, $secondFile, 1, isNested($firstFile));

    return selectFormatter($differ, $format);
}

function compareFiles(array $firstFile, array $secondFile, int $depth, bool $isNested): array
{
    $keys = [...array_unique(array_merge(array_keys($firstFile), array_keys($secondFile)))];
    $sortedKeys = sort($keys, fn($left, $right) => strcmp($left, $right));

    return reduce_left(
        $sortedKeys,
        function ($item, $key, $map, $acc) use ($firstFile, $secondFile, $depth, $isNested) {

            if (array_key_exists($item, $firstFile) && array_key_exists($item, $secondFile)) {
                if (is_array($firstFile[$item]) && is_array($secondFile[$item])) {
                    $newEntry =
                    ['type' => 'internal', "key" => $item,
                        'children' => compareFiles($firstFile[$item], $secondFile[$item], $depth + 1, $isNested)] ;
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

function isNested(array $map): bool
{
    $containsArray = array_filter($map, 'is_array');

    return count($containsArray) > 0;
}
