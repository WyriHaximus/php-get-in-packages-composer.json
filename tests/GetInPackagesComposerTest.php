<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use WyriHaximus\TestUtilities\TestCase;

use function assert;
use function is_array;
use function WyriHaximus\get_in_packages_composer;

/**
 * @internal
 */
final class GetInPackagesComposerTest extends TestCase
{
    public function testConfig(): void
    {
        $config = [];
        foreach (get_in_packages_composer('config') as $package => $value) {
            assert(is_array($value));
            $config[$package->getName()] = $value;
        }

        self::assertEquals(
            [
                'wyrihaximus/get-in-packages-composer.jason' => [
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
            ],
            $config
        );
    }
}
