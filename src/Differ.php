<?php

namespace Hexlet\Code\Differ;

use function Functional\map;
use function Functional\reduce_left;
use function Hexlet\Code\Formatters\selectFormatter;
use function Hexlet\Code\Parsers\parser;

/**
 * @throws \Exception
 */
function genDiff(string $firstPath, string $secondPath, string $format = 'stylish'): string
{
    [$firstFile, $secondFile] = parser($firstPath, $secondPath);
    $differ = compareFiles($firstFile, $secondFile, 1, $format);

    return selectFormatter($differ, $format);
}

function compareFiles(array $firstFile, array $secondFile, int $depth): array
{
    $keys = [...array_unique(array_merge(array_keys($firstFile), array_keys($secondFile)))];

    if ($depth !== 1) {
        sort($keys);
    }

    return reduce_left($keys, function ($item, $key, $map, $acc) use ($firstFile, $secondFile, $depth) {

        if (array_key_exists($item, $firstFile) && array_key_exists($item, $secondFile)) {
            if (is_array($firstFile[$item]) && is_array($secondFile[$item])) {
                $result = compareFiles($firstFile[$item], $secondFile[$item], $depth + 1);
                if (!empty($result)) {
                    $acc["$item"] = $result;
                }
            } elseif ($firstFile[$item] === $secondFile[$item]) {
                $acc["$item"] = $firstFile[$item];
            } else {
                $acc["- $item"] = $firstFile[$item];
                $acc["+ $item"] = $secondFile[$item];
            }
        } elseif (array_key_exists($item, $firstFile)) {
            $acc["- $item"] = $firstFile[$item];
        } elseif (array_key_exists($item, $secondFile)) {
            $acc["+ $item"] = $secondFile[$item];
        }
        return $acc;
    }, []);
}
