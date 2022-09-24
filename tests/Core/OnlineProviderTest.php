<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\ISO\ISOListLoader;

/**
 * Class OnlineProviderTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris@gmail.com>
 */
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
