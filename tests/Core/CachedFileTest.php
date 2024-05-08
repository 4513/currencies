<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\ISO\Exceptions\InvalidCacheDirException;
use MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException;
use MiBo\Currencies\ISO\ISOLocalListLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * Class CachedFileTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 */
#[CoversClass(ISOLocalListLoader::class)]
#[Small]
class CachedFileTest extends TestCase
{
    protected const DIR_TMP = __DIR__ . "/../../storage/tmp/";

    protected static ISOLocalListLoader $loader;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass(): void
    {
        if (!is_dir(self::DIR_TMP)) {
            mkdir(static::DIR_TMP, 0770, true);
        }

        self::$loader = new ISOLocalListLoader(static::DIR_TMP);
    }

    public function testDownload(): void
    {
        self::assertTrue($this->getLoader()->updateFile());

        self::assertFileExists(static::DIR_TMP . ISOLocalListLoader::FILE_NAME);
    }

    public function testCacheDir(): void
    {
        self::assertSame(self::DIR_TMP, $this->getLoader()->getCacheDir());
        $this->getLoader()->setCacheDir(__DIR__ . "/../../storage/");
        self::assertSame(__DIR__ . "/../../storage/", $this->getLoader()->getCacheDir());
        $this->getLoader()->setCacheDir(self::DIR_TMP);
    }

    public function testSetResources(): void
    {
        self::expectException(UnavailableCurrencyListException::class);
        self::expectExceptionMessage("Cannot use custom list of ISO currencies!");

        $this->getLoader()->setResources("");
    }

    public function testAddResource(): void
    {
        self::expectException(UnavailableCurrencyListException::class);
        self::expectExceptionMessage("Cannot use custom list of ISO currencies!");

        $this->getLoader()->addResource("");
    }

    public function testLoop(): void
    {
        foreach ($this->getLoader()->loop() as $object) {
            self::assertIsObject($object);

            self::assertNotNull($object->CtryNm);

            self::assertNotNull($object->CcyNm);

            return;
        }

        self::fail("Failed to loop the list.");
    }

    public function testCache(): void
    {
        static::tearDownAfterClass();

        $loader = new ISOLocalListLoader(static::DIR_TMP);

        self::assertEmpty($loader->getResources());

        $loader->updateFile();

        self::assertNotEmpty($loader->getResources());

        $loader = new ISOLocalListLoader(static::DIR_TMP);

        self::assertNotEmpty($loader->getResources());
    }

    public function testInvalidDirectory(): void
    {
        $cacheDir = static::DIR_TMP . "test";

        self::expectException(InvalidCacheDirException::class);
        self::expectExceptionMessage("Directory '$cacheDir' does not exist!");

        new ISOLocalListLoader($cacheDir);
    }

    public function testLoopingCachedFile(): void
    {
        self::expectNotToPerformAssertions();

        $this->getLoader()->updateFile();

        $this->getLoader()->loop();
    }

    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass(): void
    {
        if (file_exists($file = static::DIR_TMP . ISOLocalListLoader::FILE_NAME)) {
            unlink($file);
        }
    }

    /**
     * @return \MiBo\Currencies\ISO\ISOLocalListLoader
     */
    public function getLoader(): ISOLocalListLoader
    {
        return self::$loader;
    }
}
