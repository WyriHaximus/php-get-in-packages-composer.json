<?php

declare(strict_types=1);

namespace WyriHaximus;

use Composer\Package\PackageInterface;

/** @return iterable<PackageInterface, mixed> */
function get_in_packages_composer(string $path, bool $includeRoot = true): iterable
{
    return GetInPackages::composer($path, $includeRoot);
}

/** @return iterable<string, mixed> */
function from_get_in_packages_composer(string $path, bool $includeRoot = true): iterable
{
    return GetInPackages::fromComposer($path, $includeRoot);
}

/** @return iterable<string> */
function get_in_packages_composer_path(string $path, bool $includeRoot = true): iterable
{
    return GetInPackages::composerPath($path, $includeRoot);
}

/** @return iterable<string, mixed> */
function get_in_packages_composer_with_path(string $path, bool $includeRoot = true): iterable
{
    return GetInPackages::composerWithPath($path, $includeRoot);
}
