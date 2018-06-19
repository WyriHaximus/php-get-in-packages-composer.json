<?php declare(strict_types=1);

namespace WyriHaximus\Tests;

use ApiClients\Tools\TestUtilities\TestCase;
use Composed\Package;
use function WyriHaximus\get_in_packages_composer_path;

final class GetInPackagesComposerPathTest extends TestCase
{
    public function testConfig()
    {
        $config = [];
        /**
         * @var Package $package
         * @var array   $value
         */
        foreach (get_in_packages_composer_path('autoload.files') as $path) {
            $config[$path] = $path;
        }
        self::assertSame(
            [
                dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions_include.php' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions_include.php',
                dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'igorw' . DIRECTORY_SEPARATOR . 'get-in' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'get_in.php' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'igorw' . DIRECTORY_SEPARATOR . 'get-in' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'get_in.php',
                dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'joshdifabio' . DIRECTORY_SEPARATOR . 'composed' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions_include.php' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'joshdifabio' . DIRECTORY_SEPARATOR . 'composed' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions_include.php',
            ],
            $config
        );
    }
}
