<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\ISO\ISOLocalListLoader;
use PHPUnit\Framework\TestCase;

/**
 * Class CachedFileTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris@gmail.com>
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
     * @small
     *
     * @covers ::updateFile
     *
     * @return void
     */
    public function testDownload()
    {
        $this->assertTrue($this->getLoader()->updateFile());

        $this->assertFileExists(static::DIR_TMP . ISOLocalListLoader::FILE_NAME);
    }

    /**
     * @small
     *
     * @covers ::loop
     *
     * @return void
     * @throws \MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException
     */
    public function testLoop()
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
