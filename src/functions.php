<?php

declare(strict_types=1);

namespace WyriHaximus;

use Composer\InstalledVersions;
use Composer\Package\Loader\ArrayLoader;
use Composer\Package\PackageInterface;
use ComposerLocator;
use ComposerPackages\Packages;

use function explode;
use function igorw\get_in;
use function is_iterable;
use function Safe\file_get_contents;
use function Safe\json_decode;

use const DIRECTORY_SEPARATOR;

/**
 * @return iterable<PackageInterface, mixed>
 */
function get_in_packages_composer(string $path, bool $includeRoot = true): iterable
{
    $packages = [];
    if ($includeRoot) {
        $packages[Packages::ROOT_PACKAGE_NAME]            = (array) json_decode(file_get_contents(ComposerLocator::getPath(Packages::ROOT_PACKAGE_NAME) . DIRECTORY_SEPARATOR . 'composer.json'), true);
        $packages[Packages::ROOT_PACKAGE_NAME]['version'] = InstalledVersions::getPrettyVersion(Packages::ROOT_PACKAGE_NAME);
    }

    foreach (Packages::packages() as $name => $configuration) {
        $packages[$name]            = $configuration;
        $packages[$name]['version'] = InstalledVersions::getPrettyVersion($name);
    }

    foreach ($packages as $package) {
        $config = get_in($package, explode('.', $path));

        if ($config === null) {
            continue;
        }

        yield (new ArrayLoader())->load($package) => $config;
    }
}

/**
 * @return iterable<string, mixed>
 */
function from_get_in_packages_composer(string $path, bool $includeRoot = true): iterable
{
    foreach (get_in_packages_composer($path, $includeRoot) as $items) {
        yield from (array) $items;
    }
}

/**
 * @return iterable<string>
 */
function get_in_packages_composer_path(string $path, bool $includeRoot = true): iterable
{
    foreach (get_in_packages_composer($path, $includeRoot) as $package => $items) {
        if (! is_iterable($items)) {
            continue;
        }

        foreach ($items as $item => $itemPath) {
            yield ComposerLocator::getPath($package->getName()) . DIRECTORY_SEPARATOR . $itemPath;
        }
    }
}

/**
 * @return iterable<string, mixed>
 */
function get_in_packages_composer_with_path(string $path, bool $includeRoot = true): iterable
{
    foreach (get_in_packages_composer($path, $includeRoot) as $package => $items) {
        if (! is_iterable($items)) {
            continue;
        }

        foreach ($items as $item => $itemPath) {
            yield ComposerLocator::getPath($package->getName()) . DIRECTORY_SEPARATOR . $itemPath => $item;
        }
    }
}
