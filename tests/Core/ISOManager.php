<?php

namespace MiBo\Currencies\CurrencyFactory\Tests;

use MiBo\Currencies\ISO\ISOCurrency;
use MiBo\Currencies\ISO\ISOCurrencyProvider;
use MiBo\Currencies\ISO\ISOListLoader;
use MiBo\Currencies\ISO\ISOCurrencyManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Class ISOManager
 *
 * @package MiBo\Currencies\CurrencyFactory\Tests
 *
 * @author Michal Boris <michal.boris@gmail.com>
 *
 * @coversDefaultClass \MiBo\Currencies\ISO\ISOCurrencyManager
 */
class ISOManager extends TestCase
{
    private static ISOCurrencyManager $manager;
    private static ISOCurrency $validCurrency;
    private static ISOCurrency $invalidCurrency;

    public static function setUpBeforeClass(): void
    {
        $logger = new NullLogger();

        $provider = new ISOCurrencyProvider(
            // @phpcs:ignore
            new ISOListLoader(ISOListLoader::SOURCE_LOCAL),
            $logger
        );

        self::$manager = new ISOCurrencyManager($provider, $logger);

        self::$validCurrency = new ISOCurrency(
            "Euro",
            "EUR",
            "978",
            2
        );

        self::$invalidCurrency = new ISOCurrency(
            "Euro",
            "EUB",
            "970",
            2
        );
    }

    /**
     * @small
     *
     * @covers ::isCurrencyValid
     * @covers ::isCurrencyISO
     *
     * @return void
     */
    public function testValidCurrencies(): void
    {
        $this->assertTrue($this->getManager()->isCurrencyValid($this->getValidCurrency()));
        $this->assertTrue($this->getManager()->isCurrencyISO($this->getValidCurrency()));

        $this->assertFalse($this->getManager()->isCurrencyValid($this->getInvalidCurrency()));
        $this->assertFalse($this->getManager()->isCurrencyISO($this->getInvalidCurrency()));
    }

    /**
     * @return ISOCurrencyManager
     */
    public function getManager(): ISOCurrencyManager
    {
        return self::$manager;
    }

    /**
     * @return ISOCurrency
     */
    public function getValidCurrency(): ISOCurrency
    {
        return self::$validCurrency;
    }

    /**
     * @return ISOCurrency
     */
    public function getInvalidCurrency(): ISOCurrency
    {
        return self::$invalidCurrency;
    }
}
