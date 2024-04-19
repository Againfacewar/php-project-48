<?php

namespace Hexlet\Code\Formatters\Plain;

use function Functional\flatten;
use function Functional\map;
use function Functional\reduce_left;

function render(array $diff): string
{
    /**
     * @throws \Exception
     */
    $iter = function ($diff, $path = '') use (&$iter) {

        return reduce_left($diff, function ($item, $key, $map, $acc) use ($iter, $path) {
            $keyWithoutPrefix = removePrefix($key);
            $path = str_starts_with($key, "-") || str_starts_with($key, "+")
                ? "$path.$keyWithoutPrefix" : "$path.$key";

            if (str_starts_with($key, "-") || str_starts_with($key, "+")) {
                if (str_starts_with($key, "-") && array_key_exists(revertKey($key), $map)) {
                    $acc[] =
                        buildDifferString('update', valueToString($item), $path, valueToString($map[revertKey($key)]));
                } elseif (str_starts_with($key, "-")) {
                    $acc[] = buildDifferString('remove', valueToString($item), $path);
                } elseif (str_starts_with($key, "+") && !array_key_exists(revertKey($key), $map)) {
                    $acc[] = buildDifferString('add', valueToString($item), $path);
                }
            } elseif (is_array($item)) {
                $acc[] = $iter($item, $path);
            }

            return $acc;
        }, []);
    };
    $result = flatten($iter($diff));

    return implode("\n", $result);
}

/**
 * @throws \Exception
 */
function valueToString(mixed $val): string
{
    return match (true) {
        is_numeric($val) => $val,
        is_string($val) => "'$val'",
        is_bool($val) => $val ? 'true' : 'false',
        is_null($val) => 'null',
        is_array($val) => '[complex value]',
        default => throw new \Exception("Unsupported data type.")
    };
}

function revertKey(string $key): string
{
    $cleanPath = removePrefix($key);

    return match (true) {
        str_starts_with($key, '-') => "+ $cleanPath",
        str_starts_with($key, '+') => "- $cleanPath",
        default => throw new \Exception('Unexpected match value'),
    };
}

function buildDifferString(string $type, string $value1, string $path, string $value2 = ''): string
{
    $pathWithoutDot = substr($path, 1);
    return match ($type) {
        'update' => "Property '$pathWithoutDot' was updated. From $value1 to $value2",
        'remove' => "Property '$pathWithoutDot' was removed",
        'add' => "Property '$pathWithoutDot' was added with value: $value1",
    };
}

function removePrefix(string $val): string
{
    return substr($val, 2);
}
