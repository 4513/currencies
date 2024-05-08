<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\ISO\ISOListLoader;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * Class OnlineProviderTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 */
#[CoversClass(ISOListLoader::class)]
class OnlineProviderTest extends ProviderTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$provider
            ->getLoader()
            ->setResources(ISOListLoader::SOURCE_WEB);
    }
}
