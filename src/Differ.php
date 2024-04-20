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
                    $result =
                    ['type' => 'internal', "key" => $item,
                        'children' => compareFiles($firstFile[$item], $secondFile[$item], $depth + 1, $isNested)] ;
                    if (!empty($result)) {
                        $acc[] = $result;
                    }
                } elseif ($firstFile[$item] === $secondFile[$item]) {
                    $acc[] = ['type' => 'unchanged', 'key' => $item, 'value' => $firstFile[$item]];
                } else {
                    $acc[] =
                    ['type' => 'updated', 'key' => $item,
                        'value' => ['first' => $firstFile[$item], 'second' => $secondFile[$item]]];
                }
            } elseif (array_key_exists($item, $firstFile)) {
                $acc[] = ['type' => 'removed', 'key' => $item, 'value' => $firstFile[$item]];
            } elseif (array_key_exists($item, $secondFile)) {
                $acc[] = ['type' => 'added', 'key' => $item, 'value' => $secondFile[$item]];
            }
            return $acc;
        },
        []
    );
}

function isNested(array $map): bool
{
    $containsArray = array_filter($map, 'is_array');

    return count($containsArray) > 0;
}
