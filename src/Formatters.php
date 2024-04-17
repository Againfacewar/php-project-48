<?php

namespace Hexlet\Code\Formatters;

use function Hexlet\Code\Formatters\Stylish\render as stylish;
use function Hexlet\Code\Formatters\Plain\render as plain;

/**
 * @throws \Exception
 */
function selectFormatter(array $diff, string $format): string
{
    return match ($format) {
        "stylish" => stylish($diff),
        "plain" => plain($diff),
        default => throw new \Exception("Unsupported format"),
    };
}