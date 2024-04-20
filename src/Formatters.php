<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\render as toStylish;
use function Differ\Formatters\Plain\render as toPlain;

/**
 * @throws \Exception
 */
function selectFormatter(array $diff, string $format): string
{
    return match ($format) {
        "stylish" => toStylish($diff),
        "plain" => toPlain($diff),
        "json" =>  json_encode($diff, JSON_THROW_ON_ERROR),
        default => throw new \Exception("Unsupported format"),
    };
}
