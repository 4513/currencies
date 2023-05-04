<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\CurrencyInterface;
use MiBo\Currencies\ISO\ISOCurrency;
use MiBo\Currencies\ISO\ISOCurrencyProvider;
use MiBo\Currencies\ISO\ISOListLoader;
use MiBo\Currencies\ISO\ISOCurrencyManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

/**
 * Class ISOManager
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
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
     * @covers ::__construct
     * @covers ::getProvider
     *
     * @return void
     */
    public function testManager(): void
    {
        $manager = new ISOCurrencyManager(
            new ISOCurrencyProvider(new ISOListLoader(ISOListLoader::SOURCE_LOCAL), new NullLogger()),
            new NullLogger()
        );

        $this->assertSame([ISOListLoader::SOURCE_LOCAL], $manager->getProvider()->getLoader()->getResources());
    }

    /**
     * @small
     *
     * @covers ::isCurrencyValid
     * @covers ::isCurrencyISO
     * @covers ::getLogger
     *
     * @return void
     */
    public function testValidCurrencies(): void
    {
        $this->assertTrue($this->getManager()->isCurrencyValid($this->getValidCurrency()));
        $this->assertTrue($this->getManager()->isCurrencyISO($this->getValidCurrency()));

        $this->assertTrue($this->getManager()->isCurrencyValid($this->getInvalidCurrency()));
        $this->assertFalse($this->getManager()->isCurrencyISO($this->getInvalidCurrency()));

        $this->assertFalse($this->getManager()->isCurrencyISO(
            new ISOCurrency("TEST", "TTT", "012", 0)
        ));

        $this->assertFalse($this->getManager()->isCurrencyISO(
            new class implements CurrencyInterface {
                public function getName(): string { return ""; }
                public function getCode(): string { return ""; }
                public function getAlphabeticalCode(): string { return ""; }
                public function getNumericalCode(): string { return true; }
                public function getMinorUnitRate(): ?int { return null; }
                public function is(CurrencyInterface $currency): bool { return false; }
                public function __toString(): string { return ""; }
            }
        ));
    }

    /**
     * @return \MiBo\Currencies\ISO\ISOCurrencyManager
     */
    public function getManager(): ISOCurrencyManager
    {
        return self::$manager;
    }

    /**
     * @return \MiBo\Currencies\ISO\ISOCurrency
     */
    public function getValidCurrency(): ISOCurrency
    {
        return self::$validCurrency;
    }

    /**
     * @return \MiBo\Currencies\ISO\ISOCurrency
     */
    public function getInvalidCurrency(): ISOCurrency
    {
        return self::$invalidCurrency;
    }
}
