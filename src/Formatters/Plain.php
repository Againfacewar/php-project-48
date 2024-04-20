<?php

namespace Differ\Formatters\Plain;

use function Functional\flatten;
use function Functional\reduce_left;
use function Differ\Helpers\getValue;
use function Differ\Helpers\getChildren;
use function Differ\Helpers\getType;

function render(array $diff): string
{
    /**
     * @throws \Exception
     */
    $iter = function ($diff, $path = '') use (&$iter) {

        return reduce_left($diff, function ($item, $key, $map, $acc) use ($iter, $path) {
            if (getType($item) !== 'unchanged') {
                $newEntry = buildDifferString($item, $path, $iter);
            }

            return isset($newEntry) ? array_merge($acc, [$newEntry]) : $acc;
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
        is_numeric($val), is_string($val), is_bool($val) => var_export($val, true),
        is_null($val) => 'null',
        is_array($val) => '[complex value]',
        default => throw new \Exception("Unsupported data type.")
    };
}
/**
 * @throws \Exception
 */
function buildDifferString(array $node, string $path, callable $fn): mixed
{
    $nestedPath = "{$path}{$node['key']}.";
    $newPath = "{$path}{$node['key']}";

    return match (getType($node)) {
        'updated' => sprintf(
            "Property '%s' was updated. From %s to %s",
            $newPath, valueToString(getValue($node)['first']), valueToString(getValue($node)['second'])
        ),
        'removed' => sprintf("Property '%s' was removed", $newPath),
        'added' => sprintf("Property '%s' was added with value: %s", $newPath, valueToString(getValue($node))),
        'internal' => $fn(getChildren($node), $nestedPath),
        default => throw new \Exception("Unsupported type"),
    };
}
