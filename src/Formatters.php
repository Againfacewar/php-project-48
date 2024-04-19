<?php

namespace Hexlet\Code\Formatters;

use function Hexlet\Code\Formatters\Stylish\render as toStylish;
use function Hexlet\Code\Formatters\Plain\render as toPlain;
use function Hexlet\Code\Formatters\Json\render as toJson;

/**
 * @throws \Exception
 */
function selectFormatter(array $diff, string $format): string
{
    return match ($format) {
        "stylish" => toStylish($diff),
        "plain" => toPlain($diff),
        "json" => json_encode($diff, JSON_PRETTY_PRINT),
        default => throw new \Exception("Unsupported format"),
    };
}
