<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\CurrencyInterface;
use MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException;
use MiBo\Currencies\ISO\Exceptions\NoUniversalCurrencyException;
use MiBo\Currencies\ISO\ISOCurrency;
use MiBo\Currencies\ISO\ISOCurrencyProvider;
use MiBo\Currencies\ISO\ISOListLoader;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Class ProviderTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris@gmail.com>
 *
 * @coversDefaultClass \MiBo\Currencies\CurrencyProvider
 */
class ProviderTest extends TestCase
{
    protected static ISOCurrencyProvider $provider;
    private static ISOCurrency $currency;

    public static function setUpBeforeClass(): void
    {
        self::$provider = new ISOCurrencyProvider(
            new ISOListLoader(ISOListLoader::SOURCE_LOCAL),
            new NullLogger()
        );

        self::$currency = new ISOCurrency(
            "Euro",
            "EUR",
            "978",
            2
        );
    }

    /**
     * @small
     *
     * @covers ::findByName
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findByName
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findBy
     *
     * @return void
     * @throws NoUniversalCurrencyException
     * @throws InvalidCurrencyException
     */
    public function testFindByName(): void
    {
        $foundCurrency = $this->getProvider()
            ->findByName($this->getCurrency()->getName());

        $this->assertTrue($this->getCurrency()->is($foundCurrency));
    }

    /**
     * @small
     *
     * @covers ::findByAlphabeticalCode
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findByAlphabeticalCode
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findBy
     *
     * @return void
     * @throws InvalidCurrencyException
     * @throws NoUniversalCurrencyException
     */
    public function testFindByAlphabeticalCode(): void
    {
        $foundCurrency = $this->getProvider()
            ->findByAlphabeticalCode($this->getCurrency()->getAlphabeticalCode());

        $this->assertTrue($this->getCurrency()->is($foundCurrency));
    }

    /**
     * @small
     *
     * @covers ::findByNumericalCode
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findByNumericalCode
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findBy
     *
     * @return void
     * @throws InvalidCurrencyException
     * @throws NoUniversalCurrencyException
     */
    public function testFindByNumericalCode(): void
    {
        $foundCurrency = $this->getProvider()
            ->findByNumericalCode($this->getCurrency()->getNumericalCode());

        $this->assertTrue($this->getCurrency()->is($foundCurrency));
    }

    /**
     * @small
     *
     * @covers ::findByCountry
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findByCountry
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findBy
     *
     * @return void
     * @throws InvalidCurrencyException
     * @throws NoUniversalCurrencyException
     */
    public function testFindByCountry(): void
    {
        $foundCurrencies = $this->getProvider()
            ->findByCountry("SLOVAKIA");

        $this->assertNotEmpty($foundCurrencies);

        $this->assertSame(1, count($foundCurrencies));

        $foundCurrency = $foundCurrencies[0];

        $this->assertTrue($this->getCurrency()->is($foundCurrency));
    }

    /**
     * @small
     *
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findBy
     *
     * @return void
     * @throws InvalidCurrencyException
     * @throws NoUniversalCurrencyException
     */
    public function testMissingCurrency(): void
    {
        $this->expectExceptionMessage("The ISO currency could not be found!");

        $this->getProvider()->findByNumericalCode("000");
    }

    /**
     * @small
     *
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findBy
     *
     * @return void
     * @throws InvalidCurrencyException
     * @throws NoUniversalCurrencyException
     */
    public function testNoUniversalCurrency(): void
    {
        $this->expectException(NoUniversalCurrencyException::class);

        $this->getProvider()->findByCountry("ANTARCTICA");
    }

    /**
     * @return ISOCurrencyProvider
     */
    public function getProvider(): ISOCurrencyProvider
    {
        return self::$provider;
    }

    /**
     * @return ISOCurrency
     */
    public function getCurrency(): CurrencyInterface
    {
        return self::$currency;
    }
}
