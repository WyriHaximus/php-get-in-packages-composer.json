<?php declare(strict_types=1);

namespace WyriHaximus;

use Composed\Package;
use function Composed\packages;

function get_in_packages_composer(string $path, bool $includeRoot = true): iterable
{
    /** @var Package $package */
    foreach (packages($includeRoot) as $package) {
        $config = $package->getConfig(\explode('.', $path));

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

function get_in_packages_composer_path(string $path, bool $includeRoot = true): iterable
{
    /**
     * @var Package $package
     * @var mixed   $items
     */
    foreach (get_in_packages_composer($path, $includeRoot) as $package => $items) {
        /**
         * @var mixed  $item
         * @var string $path
         */
        foreach ($items as $item => $itemPath) {
            yield $package->getPath($itemPath);
        }
    }
}

function get_in_packages_composer_with_path(string $path, bool $includeRoot = true): iterable
{
    /**
     * @var Package $package
     * @var mixed   $items
     */
    foreach (get_in_packages_composer($path, $includeRoot) as $package => $items) {
        /**
         * @var mixed  $item
         * @var string $path
         */
        foreach ($items as $item => $itemPath) {
            yield $package->getPath($itemPath) => $item;
        }
    }
}
