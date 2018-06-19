<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use ApiClients\Tools\TestUtilities\TestCase;
use Composed\Package;
use function WyriHaximus\get_in_packages_composer;

final class GetInPackagesComposerTest extends TestCase
{
    public function testConfig()
    {
        $config = [];
        /**
         * @var Package $package
         * @var array   $value
         */
        foreach (get_in_packages_composer('config') as $package => $value) {
            $config[$package->getName()] = $value;
        }
        self::assertSame(
            [
                'wyrihaximus/get-in-packages-composer.jason' => [
                    'sort-packages' => true,
                    'platform' => [
                        'php' => '7.2',
                    ],
                ],
            ],
            $config
        );
    }
}
