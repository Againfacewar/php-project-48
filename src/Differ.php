<?php

namespace Hexlet\Code\Differ;

use function Functional\map;
use function Functional\reduce_left;

/**
 * @throws \Exception
 */
function genDiff(array $firstMap, array $secondMap, string $format): string
{
    return diffToString(compareFiles($firstMap, $secondMap));
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
