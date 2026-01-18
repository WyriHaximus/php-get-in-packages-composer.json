<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\Attributes\Test;
use WyriHaximus\TestUtilities\TestCase;

use function assert;
use function is_array;
use function WyriHaximus\get_in_packages_composer;

/** @internal */
final class GetInPackagesComposerTest extends TestCase
{
    #[Test]
    public function config(): void
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
                        'drupol/composer-packages' => true,
                        'mindplay/composer-locator' => true,
                        'phpstan/extension-installer' => true,
                        'wyrihaximus/makefiles' => true,
                        'wyrihaximus/test-utilities' => true,
                    ],
                    'platform' => ['php' => '8.4.13'],
                    'sort-packages' => true,
                ],
                'composer/composer' => [
                    'platform' => ['php' => '7.2.5'],
                    'platform-check' => false,
                ],
            ],
            $config,
        );
    }
}
