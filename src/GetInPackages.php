<?php

declare(strict_types=1);

namespace WyriHaximus;

use Composer\InstalledVersions;
use Composer\Package\Loader\ArrayLoader;
use Composer\Package\PackageInterface;
use ComposerLocator;
use ComposerPackages\Packages;
use RuntimeException;

use function assert;
use function explode;
use function file_get_contents;
use function igorw\get_in;
use function is_iterable;
use function is_string;
use function json_decode;

use const DIRECTORY_SEPARATOR;

final class GetInPackages
{
    /** @return iterable<PackageInterface, mixed> */
    public static function composer(string $path, bool $includeRoot = true): iterable
    {
        $packages = [];
        if ($includeRoot) {
            $packages[Packages::ROOT_PACKAGE_NAME]            = (static function (): array {
                $rootPackageFileContents = file_get_contents(ComposerLocator::getPath(Packages::ROOT_PACKAGE_NAME) . DIRECTORY_SEPARATOR . 'composer.json');
                if (! is_string($rootPackageFileContents)) {
                    throw new RuntimeException('Unable to read root composer.json');
                }

                return (array) json_decode($rootPackageFileContents, true);
            })();
            $packages[Packages::ROOT_PACKAGE_NAME]['version'] = InstalledVersions::getPrettyVersion(Packages::ROOT_PACKAGE_NAME);
        }

        foreach (Packages::packages() as $name => $configuration) {
            assert(is_string($name));
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

    /** @return iterable<string, mixed> */
    public static function fromComposer(string $path, bool $includeRoot = true): iterable
    {
        foreach (self::composer($path, $includeRoot) as $items) {
            yield from (array) $items;
        }
    }

    /** @return iterable<string> */
    public static function composerPath(string $path, bool $includeRoot = true): iterable
    {
        foreach (self::composer($path, $includeRoot) as $package => $items) {
            if (! is_iterable($items)) {
                continue;
            }

            foreach ($items as $item => $itemPath) {
                yield ComposerLocator::getPath($package->getName()) . DIRECTORY_SEPARATOR . $itemPath;
            }
        }
    }

    /** @return iterable<string, mixed> */
    public static function composerWithPath(string $path, bool $includeRoot = true): iterable
    {
        foreach (self::composer($path, $includeRoot) as $package => $items) {
            if (! is_iterable($items)) {
                continue;
            }

            foreach ($items as $item => $itemPath) {
                yield ComposerLocator::getPath($package->getName()) . DIRECTORY_SEPARATOR . $itemPath => $item;
            }
        }
    }
}
