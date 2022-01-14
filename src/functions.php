<?php

declare(strict_types=1);

namespace WyriHaximus;

use Composed\Package;

use function Composed\packages;
use function explode;

/**
 * @return iterable<Package, mixed>
 */
function get_in_packages_composer(string $path, bool $includeRoot = true): iterable
{
    foreach (packages($includeRoot) as $package) {
//        assert($package instanceof Package);
        $config = $package->getConfig(explode('.', $path));

        if ($config === null) {
            continue;
        }

        yield $package => $config;
    }
}

/**
 * @return iterable<string, mixed>
 */
function from_get_in_packages_composer(string $path, bool $includeRoot = true): iterable
{
    foreach (get_in_packages_composer($path, $includeRoot) as $items) {
        yield from $items;
    }
}

/**
 * @return iterable<string>
 */
function get_in_packages_composer_path(string $path, bool $includeRoot = true): iterable
{
    foreach (get_in_packages_composer($path, $includeRoot) as $package => $items) {
        foreach ($items as $item => $itemPath) {
            yield $package->getPath($itemPath);
        }
    }
}

/**
 * @return iterable<string, mixed>
 */
function get_in_packages_composer_with_path(string $path, bool $includeRoot = true): iterable
{
    foreach (get_in_packages_composer($path, $includeRoot) as $package => $items) {
        foreach ($items as $item => $itemPath) {
            yield $package->getPath($itemPath) => $item;
        }
    }
}
