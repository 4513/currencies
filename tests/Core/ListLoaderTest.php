<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException;
use MiBo\Currencies\ISO\ISOListLoader;
use PHPUnit\Framework\TestCase;

/**
 * Class ListLoaderTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris@gmail.com>
 *
 * @coversDefaultClass \MiBo\Currencies\ListLoader
 */
class ListLoaderTest extends TestCase
{
    private static ISOListLoader $loader;

    public static function setUpBeforeClass(): void
    {
        self::$loader = new ISOListLoader(ISOListLoader::SOURCE_LOCAL);
    }

    protected function setUp(): void
    {
        $this->getLoader()->setResources(ISOListLoader::SOURCE_LOCAL);
    }

    /**
     * @small
     *
     * @covers ::setResources
     * @covers \MiBo\Currencies\ISO\ISOListLoader::setResources
     *
     * @return void
     * @throws UnavailableCurrencyListException
     */
    public function testChangeResourceToLocal(): void
    {
        $this->getLoader()->setResources(ISOListLoader::SOURCE_LOCAL);

        $this->assertSame(1, count($this->getLoader()->getResources()));
        $this->assertSame(ISOListLoader::SOURCE_LOCAL, $this->getLoader()->getResources()[0]);

        $this->getLoader()->addResource(ISOListLoader::SOURCE_LOCAL);

        $this->assertSame(1, count($this->getLoader()->getResources()));
        $this->assertSame(ISOListLoader::SOURCE_LOCAL, $this->getLoader()->getResources()[0]);
    }

    /**
     * @small
     *
     * @covers ::setResources
     * @covers \MiBo\Currencies\ISO\ISOListLoader::setResources
     *
     * @return void
     * @throws UnavailableCurrencyListException
     */
    public function testChangeResourceToOnline(): void
    {
        $this->getLoader()->setResources(ISOListLoader::SOURCE_WEB);

        $this->assertSame(1, count($this->getLoader()->getResources()));
        $this->assertSame(ISOListLoader::SOURCE_WEB, $this->getLoader()->getResources()[0]);

        $this->getLoader()->addResource(ISOListLoader::SOURCE_WEB);

        $this->assertSame(1, count($this->getLoader()->getResources()));
        $this->assertSame(ISOListLoader::SOURCE_WEB, $this->getLoader()->getResources()[0]);
    }

    /**
     * @small
     *
     * @covers ::addResource
     * @covers \MiBo\Currencies\ISO\ISOListLoader::addResource
     *
     * @return void
     * @throws UnavailableCurrencyListException
     */
    public function testChangeResourceToInvalid(): void
    {
        $this->expectExceptionMessage("Cannot use custom list of ISO currencies!");

        $this->getLoader()->addResource("aRandomResource");
    }

    /**
     * @small
     *
     * @covers ::setResources
     * @covers \MiBo\Currencies\ISO\ISOListLoader::setResources
     *
     * @return void
     * @throws \MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException
     */
    public function testChangeResourcesToInvalid(): void
    {
        $this->expectExceptionMessage("Cannot use custom list of ISO currencies!");

        $this->getLoader()->setResources("aRandomResource");
    }

    /**
     * @small
     *
     * @covers ::loop
     * @covers \MiBo\Currencies\ISO\ISOListLoader::loop
     *
     * @return void
     * @throws UnavailableCurrencyListException
     */
    public function testLoop(): void
    {
        $this->assertTrue(is_file(ISOListLoader::SOURCE_LOCAL));

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
     * @covers ::getResources
     * @covers \MiBo\Currencies\ISO\ISOListLoader::getResources
     *
     * @return void
     */
    public function testResource(): void
    {
        $this->assertIsArray($this->getLoader()->getResources());

        $this->assertSame(1, count($this->getLoader()->getResources()));
    }


    /**
     * @return ISOListLoader
     */
    public function getLoader(): ISOListLoader
    {
        return self::$loader;
    }
}
