<?php

namespace Hexlet\Code\Formatters\Stylish;

use function Functional\map;

function render(array $diff): string
{
    /**
     * @throws \Exception
     */
    $iter = function ($diff, $depth) use (&$iter) {
        $spacesCount = 4;

        if (!is_array($diff)) {
            return valueToString($diff);
        }

        $indentSize = $depth * $spacesCount;
        $currentIndent = str_repeat(" ", $indentSize - 2);
        $bracketIndent = str_repeat(" ", $indentSize - $spacesCount);

        $lines = map($diff, function ($item, $key) use ($iter, $depth, $currentIndent) {
            if (!str_starts_with($key, '-') && !str_starts_with($key, '+')) {
                $key = "  $key";
            }

            return "$currentIndent$key: {$iter($item, $depth + 1)}";
        });

        $result = ['{', ...$lines, "{$bracketIndent}}"];

        return implode("\n", $result);
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
        default => throw new \Exception("Unsupported data type.")
    };
}
