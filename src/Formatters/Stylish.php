<?php

namespace Differ\Formatters\Stylish;

use function Differ\Helpers\getValue;
use function Differ\Helpers\getChildren;
use function Differ\Helpers\getType;
use function Differ\Helpers\getKey;
use function Functional\map;

function calculateOffset(int $depth, int $offset = 4): array
{
    $indentSize = $depth * $offset;
    $currentIndent = str_repeat(" ", $indentSize - 2);
    $bracketIndent = str_repeat(" ", $indentSize - $offset);

    return [$currentIndent, $bracketIndent];
}

function render(array $diff): string
{
    $iter = function ($node, $depth) use (&$iter) {
        [$currentIndent, $bracketIndent] = calculateOffset($depth);

        $lines = map($node, function ($item) use ($iter, $depth, $currentIndent) {
            return buildDifferString($item, $depth, $currentIndent, $iter);
        });

        return concatLines($lines, $bracketIndent);
    };

    return $iter($diff, 1);
}

/**
 * @throws \Exception
 */
function valueToString(mixed $val): string
{
    return match (true) {
        is_string($val), is_numeric($val) => $val,
        is_bool($val) => $val ? 'true' : 'false',
        is_null($val) => 'null',
        is_array($val) => '[complex value]',
        default => throw new \Exception("Unsupported data type.")
    };
}

function buildDifferString(mixed $node, int $depth, string $currentIndent, callable $fn): string
{
    /**
     * @throws \Exception
     */
    $stringify = function (mixed $value, int $depth) use (&$stringify) {
        if (!is_array($value)) {
            return valueToString($value);
        }
        [$currentIndent, $bracketIndent] = calculateOffset($depth);
        $lines = map($value, function ($item, $key) use ($stringify, $depth, $currentIndent) {
            return "$currentIndent  $key: {$stringify($item, $depth + 1)}";
        });

        return concatLines($lines, $bracketIndent);
    };

    $key = getKey($node);
    return match (getType($node)) {
        'unchanged' => sprintf(
            "%s  %s: %s",
            $currentIndent, $key, $stringify($node['value'], $depth + 1)
        ),
        'updated' => sprintf(
            "%s- %s: %s\n%s+ %s: %s",
            $currentIndent, $key, $stringify(getValue($node)['first'], $depth + 1),
            $currentIndent, $key, $stringify(getValue($node)['second'], $depth + 1)
        ),
        'removed' => sprintf(
            "%s- %s: %s",
            $currentIndent, $key, $stringify($node['value'], $depth + 1)
        ),
        'added' => sprintf(
            "%s+ %s: %s",
            $currentIndent, $key, $stringify($node['value'], $depth + 1)
        ),
        'internal' => sprintf(
            "%s  %s: %s",
            $currentIndent,
            $key,
            $fn(getChildren($node), $depth + 1)
        )
    };
}

function concatLines(array $lines, string $bracketOffset): string
{
    return implode("\n", ['{', ...$lines, "{$bracketOffset}}"]);
}
