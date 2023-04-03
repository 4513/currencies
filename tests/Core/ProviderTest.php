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

    /**
     * @return void
     */
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
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findByName
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findBy
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findFirstBy
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::transformToCurrency
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
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::__construct
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findByAlphabeticalCode
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findBy
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findFirstBy
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::transformToCurrency
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::getLogger
     *
     * @return void
     * @throws InvalidCurrencyException
     * @throws NoUniversalCurrencyException
     */
    public function testFindByAlphabeticalCode(): void
    {
        $provider = new ISOCurrencyProvider($this->getProvider()->getLoader(), new NullLogger());

        $foundCurrency = $provider
            ->findByAlphabeticalCode($this->getCurrency()->getAlphabeticalCode());

        $this->assertTrue($this->getCurrency()->is($foundCurrency));
    }

    /**
     * @small
     *
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findByNumericalCode
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findBy
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findFirstBy
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::transformToCurrency
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
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findByCountry
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::findBy
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::transformToCurrency
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
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::transformToCurrency
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
     * @small
     *
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::getLoader
     * @covers \MiBo\Currencies\ISO\ISOCurrencyProvider::setLoader
     *
     * @return void
     */
    public function testLoader(): void
    {
        $this->assertInstanceOf(ISOListLoader::class, $this->getProvider()->getLoader());

        $loader = $this->getProvider()->getLoader();

        $this->getProvider()->setLoader(new ISOListLoader(ISOListLoader::SOURCE_LOCAL));
        $this->assertSame([ISOListLoader::SOURCE_LOCAL], $this->getProvider()->getLoader()->getResources());
        $this->getProvider()->setLoader(new ISOListLoader(ISOListLoader::SOURCE_WEB));
        $this->assertSame([ISOListLoader::SOURCE_WEB], $this->getProvider()->getLoader()->getResources());
        $this->getProvider()->setLoader($loader);
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
