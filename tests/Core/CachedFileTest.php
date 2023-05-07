<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\ISO\Exceptions\InvalidCacheDirException;
use MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException;
use MiBo\Currencies\ISO\ISOLocalListLoader;
use PHPUnit\Framework\TestCase;

/**
 * Class CachedFileTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @coversDefaultClass \MiBo\Currencies\ISO\ISOLocalListLoader
 */
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

    /**
     * @medium
     *
     * @covers ::updateFile
     * @covers ::getCacheDir
     *
     * @return void
     */
    public function testDownload(): void
    {
        $this->assertTrue($this->getLoader()->updateFile());

        $this->assertFileExists(static::DIR_TMP . ISOLocalListLoader::FILE_NAME);
    }

    /**
     * @small
     *
     * @covers ::getCacheDir
     * @covers ::setCacheDir
     *
     * @return void
     */
    public function testCacheDir(): void
    {
        $this->assertSame(self::DIR_TMP, $this->getLoader()->getCacheDir());
        $this->getLoader()->setCacheDir(__DIR__ . "/../../storage/");
        $this->assertSame(__DIR__ . "/../../storage/", $this->getLoader()->getCacheDir());
        $this->getLoader()->setCacheDir(self::DIR_TMP);
    }

    /**
     * @small
     *
     * @covers ::setResources
     *
     * @return void
     */
    public function testSetResources(): void
    {
        $this->expectException(UnavailableCurrencyListException::class);
        $this->expectExceptionMessage("Cannot use custom list of ISO currencies!");

        $this->getLoader()->setResources("");
    }

    /**
     * @small
     *
     * @covers ::addResource
     *
     * @return void
     */
    public function testAddResource(): void
    {
        $this->expectException(UnavailableCurrencyListException::class);
        $this->expectExceptionMessage("Cannot use custom list of ISO currencies!");

        $this->getLoader()->addResource("");
    }

    /**
     * @small
     *
     * @covers ::loop
     * @covers ::contractLoop
     *
     * @return void
     * @throws \MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException
     */
    public function testLoop(): void
    {
        foreach ($this->getLoader()->loop() as $object) {
            $this->assertIsObject($object);

            $this->assertNotNull($object->CtryNm);

            $this->assertNotNull($object->CcyNm);

            return;
        }

        $this->fail("Failed to loop the list.");
    }

    /**
     * @small
     *
     * @covers ::updateFile
     * @covers ::isFileCached
     * @covers ::__construct
     *
     * @return void
     */
    public function testCache(): void
    {
        static::tearDownAfterClass();

        $loader = new ISOLocalListLoader(static::DIR_TMP);

        $this->assertEmpty($loader->getResources());

        $loader->updateFile();

        $this->assertNotEmpty($loader->getResources());

        $loader = new ISOLocalListLoader(static::DIR_TMP);

        $this->assertNotEmpty($loader->getResources());
    }

    /**
     * @small
     *
     * @covers ::__construct
     *
     * @return void
     */
    public function testInvalidDirectory(): void
    {
        $cacheDir = static::DIR_TMP . "test";

        $this->expectException(InvalidCacheDirException::class);
        $this->expectExceptionMessage("Directory '$cacheDir' does not exist!");

        new ISOLocalListLoader($cacheDir);
    }

    /**
     * @medium
     *
     * @covers ::loop
     * @covers ::contractLoop
     *
     * @doesNotPerformAssertions
     *
     * @return void
     */
    public function testLoopingCachedFile(): void
    {
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
