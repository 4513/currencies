<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\ISO\ISOListLoader;
use MiBo\Currencies\ListLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * Class ListLoaderTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 */
#[CoversClass(ListLoader::class)]
#[CoversClass(ISOListLoader::class)]
#[Small]
class ListLoaderTest extends TestCase
{
    private static ISOListLoader $loader;

    /**
     * @inheritDoc
     */
    public static function setUpBeforeClass(): void
    {
        self::$loader = new ISOListLoader(ISOListLoader::SOURCE_LOCAL);
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->getLoader()->setResources(ISOListLoader::SOURCE_LOCAL);
    }

    public function testChangeResourceToLocal(): void
    {
        $this->getLoader()->setResources(ISOListLoader::SOURCE_LOCAL);

        self::assertSame(1, count($this->getLoader()->getResources()));
        self::assertSame(ISOListLoader::SOURCE_LOCAL, $this->getLoader()->getResources()[0]);

        $this->getLoader()->addResource(ISOListLoader::SOURCE_LOCAL);

        self::assertSame(1, count($this->getLoader()->getResources()));
        self::assertSame(ISOListLoader::SOURCE_LOCAL, $this->getLoader()->getResources()[0]);
    }

    public function testChangeResourceToOnline(): void
    {
        $this->getLoader()->setResources(ISOListLoader::SOURCE_WEB);

        self::assertSame(1, count($this->getLoader()->getResources()));
        self::assertSame(ISOListLoader::SOURCE_WEB, $this->getLoader()->getResources()[0]);

        $this->getLoader()->addResource(ISOListLoader::SOURCE_WEB);

        self::assertSame(1, count($this->getLoader()->getResources()));
        self::assertSame(ISOListLoader::SOURCE_WEB, $this->getLoader()->getResources()[0]);
    }

    public function testChangeResourceToInvalid(): void
    {
        $this->expectExceptionMessage("Cannot use custom list of ISO currencies!");

        $this->getLoader()->addResource("aRandomResource");
    }

    public function testChangeResourcesToInvalid(): void
    {
        $this->expectExceptionMessage("Cannot use custom list of ISO currencies!");

        $this->getLoader()->setResources("aRandomResource");
    }

    public function testLoop(): void
    {
        self::assertTrue(is_file(ISOListLoader::SOURCE_LOCAL));

        foreach ($this->getLoader()->loop() as $object) {
            self::assertIsObject($object);

            self::assertNotNull($object->CtryNm);

            self::assertNotNull($object->CcyNm);

            return;
        }

        $this->fail("Failed to loop the list.");
    }

    public function testResource(): void
    {
        self::assertIsArray($this->getLoader()->getResources());

        self::assertSame(1, count($this->getLoader()->getResources()));
    }

    public function testConstruct(): void
    {
        self::$loader = new ISOListLoader(ISOListLoader::SOURCE_LOCAL);

        self::assertSame([ISOListLoader::SOURCE_LOCAL], $this->getLoader()->getResources());
    }

    public function testResources(): void
    {
        $loader = new class extends ListLoader {
            public function loop(): array
            {
                return [];
            }
        };

        self::assertEmpty($loader->getResources());

        $loader->setResources("myResource", "anotherResource");

        self::assertSame(["myResource", "anotherResource"], $loader->getResources());

        $loader->addResource("newResource");

        self::assertSame(["myResource", "anotherResource", "newResource"], $loader->getResources());
    }

    /**
     * @return \MiBo\Currencies\ISO\ISOListLoader
     */
    public function getLoader(): ISOListLoader
    {
        return self::$loader;
    }
}
