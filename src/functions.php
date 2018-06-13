<?php

namespace WyriHaximus;

use Composed\Package;
use function Composed\packages;

function get_in_packages_composer(string $path, bool $includeRoot = true): iterable
{
    /** @var Package $package */
    foreach (packages($includeRoot) as $package) {
        $config = $package->getConfig(explode('.', $path));

        if ($config === null) {
            continue;
        }

        yield $package => $config;
    }
}

function from_get_in_packages_composer(string $path, bool $includeRoot = true): iterable
{
    foreach (get_in_packages_composer($path, $includeRoot) as $items) {
        yield from $items;
    }
}
