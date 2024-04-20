<?php

namespace Differ\Helpers;

function getValue(array $node): mixed
{
    return $node['value'];
}

function getType(array $node): string
{
    return $node['type'];
}

function getChildren(array $node): array
{
    return $node['children'];
}

function getKey(array $node): string
{
    return $node['key'];
}
