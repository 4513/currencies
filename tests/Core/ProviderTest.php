<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\CurrencyInterface;
use MiBo\Currencies\CurrencyProvider;
use MiBo\Currencies\ISO\Exceptions\NoUniversalCurrencyException;
use MiBo\Currencies\ISO\ISOCurrency;
use MiBo\Currencies\ISO\ISOCurrencyProvider;
use MiBo\Currencies\ISO\ISOListLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Medium;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Class ProviderTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 */
#[CoversClass(CurrencyProvider::class)]
#[CoversClass(ISOCurrencyProvider::class)]
#[Medium]
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

    public function testFindByName(): void
    {
        $foundCurrency = $this->getProvider()
            ->findByName($this->getCurrency()->getName());

        self::assertTrue($this->getCurrency()->is($foundCurrency));
    }

    public function testFindByAlphabeticalCode(): void
    {
        $provider = new ISOCurrencyProvider($this->getProvider()->getLoader(), new NullLogger());

        $foundCurrency = $provider
            ->findByAlphabeticalCode($this->getCurrency()->getAlphabeticalCode());

        self::assertTrue($this->getCurrency()->is($foundCurrency));
    }

    public function testFindByNumericalCode(): void
    {
        $foundCurrency = $this->getProvider()
            ->findByNumericalCode($this->getCurrency()->getNumericalCode());

        self::assertTrue($this->getCurrency()->is($foundCurrency));
    }

    public function testFindByCountry(): void
    {
        $foundCurrencies = $this->getProvider()
            ->findByCountry("SLOVAKIA");

        self::assertNotEmpty($foundCurrencies);

        self::assertSame(1, count($foundCurrencies));

        $foundCurrency = $foundCurrencies[0];

        self::assertTrue($this->getCurrency()->is($foundCurrency));
    }

    public function testMissingCurrency(): void
    {
        $this->expectExceptionMessage("The ISO currency could not be found!");

        $this->getProvider()->findByNumericalCode("000");
    }

    public function testNoUniversalCurrency(): void
    {
        $this->expectException(NoUniversalCurrencyException::class);

        $this->getProvider()->findByCountry("ANTARCTICA");
    }

    public function testLoader(): void
    {
        self::assertInstanceOf(ISOListLoader::class, $this->getProvider()->getLoader());

        $loader = $this->getProvider()->getLoader();

        $this->getProvider()->setLoader(new ISOListLoader(ISOListLoader::SOURCE_LOCAL));
        self::assertSame([ISOListLoader::SOURCE_LOCAL], $this->getProvider()->getLoader()->getResources());
        $this->getProvider()->setLoader(new ISOListLoader(ISOListLoader::SOURCE_WEB));
        self::assertSame([ISOListLoader::SOURCE_WEB], $this->getProvider()->getLoader()->getResources());
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
