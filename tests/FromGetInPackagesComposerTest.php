<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use ApiClients\Tools\TestUtilities\TestCase;
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
