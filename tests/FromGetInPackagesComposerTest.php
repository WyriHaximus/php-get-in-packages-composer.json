<?php

namespace WyriHaximus\Tests;

use ApiClients\Tools\TestUtilities\TestCase;
use Composed\Package;
use function WyriHaximus\from_get_in_packages_composer;

final class FromGetInPackagesComposerTest extends TestCase
{
    public function testConfig()
    {
        $config = [];
        foreach (from_get_in_packages_composer('config') as $key => $value) {
            $config[$key] = $value;
        }
        self::assertSame(
            [
                'sort-packages' => true,
                'platform' => [
                    'php' => '7.2',
                ],
            ],
            $config
        );
    }
}