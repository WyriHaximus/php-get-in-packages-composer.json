<?php

declare(strict_types=1);

namespace WyriHaximus\Tests;

use PHPUnit\Framework\Attributes\Test;
use WyriHaximus\TestUtilities\TestCase;

use function dirname;
use function WyriHaximus\get_in_packages_composer_with_path;

use const DIRECTORY_SEPARATOR;

/** @internal */
final class GetInPackagesComposerWithPathTest extends TestCase
{
    #[Test]
    public function config(): void
    {
        $config = [];
        foreach (get_in_packages_composer_with_path('autoload.files') as $key => $value) {
            $config[$key] = $value;
        }

        self::assertArrayHasKey(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions_include.php', $config);
        self::assertSame($config[dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions_include.php'], 0);
        self::assertArrayHasKey(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'react' . DIRECTORY_SEPARATOR . 'promise' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions_include.php', $config);
        self::assertSame($config[dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'react' . DIRECTORY_SEPARATOR . 'promise' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions_include.php'], 0);
    }
}
