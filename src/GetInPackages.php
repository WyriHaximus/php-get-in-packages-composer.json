<?php

declare(strict_types=1);

namespace WyriHaximus;

use Composer\InstalledVersions;
use Composer\Package\Loader\ArrayLoader;
use Composer\Package\PackageInterface;
use RuntimeException;

use function array_key_exists;
use function count;
use function explode;
use function file_get_contents;
use function is_array;
use function is_iterable;
use function is_string;
use function json_decode;
use function realpath;

use const DIRECTORY_SEPARATOR;

final class GetInPackages
{
    /** @return iterable<PackageInterface, mixed> */
    public static function composer(string $path, bool $includeRoot = true): iterable
    {
        /** @var array<string> $pathList */
        $pathList = explode('.', $path);
        /** @var array<string, array<string, mixed>> $packages */
        $packages = [];
        if ($includeRoot) {
            $rootPackageName                       = InstalledVersions::getRootPackage()['name'];
            $packages[$rootPackageName]            = (static function (string $rootPackageName): array {
                $rootPackageFileContents = file_get_contents(InstalledVersions::getInstallPath($rootPackageName) . DIRECTORY_SEPARATOR . 'composer.json');
                if (! is_string($rootPackageFileContents)) {
                    throw new RuntimeException('Unable to read root composer.json');
                }

                return (array) json_decode($rootPackageFileContents, true);
            })($rootPackageName);
            $packages[$rootPackageName]['version'] = InstalledVersions::getPrettyVersion($rootPackageName);
            unset($rootPackageName);
        }

        foreach (InstalledVersions::getInstalledPackages() as $name) {
            if (! InstalledVersions::isInstalled($name, false)) {
                continue;
            }

            $installPath = InstalledVersions::getInstallPath($name);
            if (! is_string($installPath)) {
                continue;
            }

            $packages[$name]            = (static function (string $installPath): array {
                $packageFileContents = file_get_contents($installPath . DIRECTORY_SEPARATOR . 'composer.json');
                if (! is_string($packageFileContents)) {
                    throw new RuntimeException('Unable to read composer.json');
                }

                return (array) json_decode($packageFileContents, true);
            })($installPath);
            $packages[$name]['version'] = InstalledVersions::getPrettyVersion($name);
        }

        foreach ($packages as $package) {
            /**
             * @todo Fix before releasing
             * @phpstan-ignore argument.type
             */
            $config = self::getConfig($package, $pathList);

            if ($config === null) {
                continue;
            }

            yield new ArrayLoader()->load($package) => $config;
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
                yield self::getPackageRealPath($package) . DIRECTORY_SEPARATOR . $itemPath;
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
                yield self::getPackageRealPath($package) . DIRECTORY_SEPARATOR . $itemPath => $item;
            }
        }
    }

    private static function getPackageRealPath(PackageInterface $package): string
    {
        $maybeRealPath = (string) InstalledVersions::getInstallPath($package->getName());

        $realPath = realpath($maybeRealPath);
        if (! is_string($realPath)) {
            return $maybeRealPath;
        }

        return $realPath;
    }

    /**
     * @param array<string, mixed> $array
     * @param array<string>        $keys
     */
    private static function getConfig(array $array, array $keys): mixed
    {
        if (count($keys) === 0) {
            return null;
        }

        // This is a micro-optimization, it is fast for non-nested keys, but fails for null values
        if (count($keys) === 1 && array_key_exists($keys[0], $array)) {
            return $array[$keys[0]];
        }

        $current = $array;
        foreach ($keys as $key) {
            if (! is_array($current) || ! array_key_exists($key, $current)) {
                return null;
            }

            $current = $current[$key];
        }

        return $current;
    }
}
