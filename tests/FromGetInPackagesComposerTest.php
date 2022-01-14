<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use WyriHaximus\TestUtilities\TestCase;

use function WyriHaximus\from_get_in_packages_composer;

/**
 * @internal
 */
final class FromGetInPackagesComposerTest extends TestCase
{
    public function testConfig(): void
    {
        $config = [];
        foreach (from_get_in_packages_composer('config') as $key => $value) {
            $config[$key] = $value;
        }

        self::assertEquals(
            [
                'allow-plugins' => [
                    'composer/package-versions-deprecated' => true,
                    'infection/extension-installer' => true,
                    'dealerdirect/phpcodesniffer-composer-installer' => true,
                    'icanhazstring/composer-unused' => true,
                    'ergebnis/composer-normalize' => true,
                ],
                'platform' => ['php' => '7.4.7'],
                'sort-packages' => true,
            ],
            $config
        );
    }
}
