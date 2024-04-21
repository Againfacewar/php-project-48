<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Functional\map;

/**
 * @throws \Exception
 */
function parser(mixed $file, string $ext): array
{
    return match ($ext) {
        'yaml', 'yml' => Yaml::parse($file),
        'json' => json_decode($file, true),
        default => throw new \Exception("Unsupported format"),
    };
}
