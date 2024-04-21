<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

use function Functional\map;

/**
 * @throws \Exception
 */
function parser(string $file, string $ext): array
{
    return match ($ext) {
        'yaml', 'yml' => Yaml::parse((string) $file),
        'json' => json_decode((string) $file, true),
        default => throw new \Exception("Unsupported format"),
    };
}
