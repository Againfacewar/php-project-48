<?php

namespace Hexlet\Code\Differ;

use function Functional\map;
use function Functional\reduce_left;

/**
 * @throws \Exception
 */
function genDiff(array $firstMap, array $secondMap, string $format)
{
//    var_dump($firstMap);
//    var_dump($secondMap);
//    return diffToString(compareFiles($firstMap, $secondMap));
    print_r(compareFiles($firstMap, $secondMap, '-'));
    $first = compareFiles($firstMap, $secondMap, '-');
    $second = compareFiles($secondMap, $firstMap, '+');
    print_r(array_merge($first, $second));
//    compareFiles($firstMap, $secondMap, '-');
}

function compareFiles(array $iterFile, array $additionalFile, $prevSymbol): array
{



    $iter = function ($iterFile, $currentNesting) use ($additionalFile, &$iter, $prevSymbol) {

        return reduce_left($iterFile, function ($item, $key, $map, $acc) use ($currentNesting, $prevSymbol, $iter) {
            $isArray = is_array($item);
//            print_r("Первый файл\n");
//            print_r("Ключ: $key\n");
//            print_r($item);
//            print_r("\n");
//            print_r("Второй файл\n");
//            print_r($currentNesting);
            if (is_array($currentNesting) && array_key_exists($key, $currentNesting)) {
                if ($isArray && is_array($currentNesting[$key])) {
                    $currentNesting = $currentNesting[$key];
                    $acc[$key] = $iter($item, $currentNesting);
                } else {
                    if ($item === $currentNesting[$key]) {
                        $acc[$key] = $item;
                    } else {
                        $acc["$prevSymbol$key"] = $item;
                    }
                }
            } else {
                $acc["$prevSymbol$key"] = $item;
            }

            return $acc;
        }, []);
    };

    return $iter($iterFile, $additionalFile);
}

function diffToString(array $map): string
{
    return "{" . PHP_EOL . "  " . implode("\n  ", $map) . PHP_EOL . "}" . PHP_EOL;
}

function valueToString(mixed $val): string
{
    return match (true) {
        is_string($val) => $val,
        is_bool($val) => $val ? 'true' : 'false',
        is_numeric($val) => $val,
        is_null($val) => 'null',
        default => throw new \Exception("Unsupported data type.")
    };
}
