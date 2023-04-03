<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\CurrencyInterface;
use MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException;
use MiBo\Currencies\ISO\ISOCurrency;
use PHPUnit\Framework\TestCase;

/**
 * Class CurrencyTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris@gmail.com>
 *
 * @coversDefaultClass \MiBo\Currencies\ISO\ISOCurrency
 */
class CurrencyTest extends TestCase
{
    /**
     * @small
     *
     * @covers ::validateISO
     * @covers ::__toString
     * @covers ::getName
     * @covers ::getAlphabeticalCode
     * @covers ::getNumericalCode
     * @covers ::getMinorUnitRate
     *
     * @return void
     */
    public function testCreateValidCurrency(): void
    {
        $currency = new ISOCurrency(
            "Test",
            "ABC",
            "012",
            null
        );

        $this->assertSame("ABC", (string) $currency);

        $this->assertSame("Test", $currency->getName());
        $this->assertSame("ABC", $currency->getAlphabeticalCode());
        $this->assertSame("012", $currency->getNumericalCode());
        $this->assertSame(null, $currency->getMinorUnitRate());
    }

    /**
     * @small
     *
     * @covers ::validateISO
     *
     * @return void
     */
    public function testCreateInvalidCurrency(): void
    {
        $this->expectException(InvalidCurrencyException::class);

        // @phpcs:disable
        $faultyCurrency = new ISOCurrency(
            "Test",
            "012",
            /** @phpstan-ignore-next-line */
            "ABC",
            null
        );
        // @phpcs:enable
    }

    /**
     * @small
     *
     * @covers ::is
     *
     * @return void
     */
    public function testIsSame(): void
    {
        $first = new ISOCurrency("TEST", "ABC", "012", null);
        $second = new ISOCurrency("TEST", "ABC", "012", null);
        $third = new ISOCurrency("TEST", "ABC", "123", null);

        $this->assertTrue($first->is($first));
        $this->assertTrue($first->is($second));

        $this->assertFalse($first->is($third));
    }

    /**
     * @small
     *
     * @covers \MiBo\Currencies\ISO\ISOCurrency::__construct
     * @covers \MiBo\Currencies\ISO\ISOCurrency::validateISO
     *
     * @return void
     */
    public function testISOValidation(): void
    {
        $currency          = new class implements CurrencyInterface {
            public function getName(): string { return ""; }
            public function getAlphabeticalCode(): string { return ""; }
            public function getMinorUnitRate(): ?int { return null; }
            public function getNumericalCode(): string { return ""; }
            public function is(CurrencyInterface $currency): bool { return false; }
            public function __toString(): string { return $this->getName(); }
        };
        $currencyClassName = get_class($currency);
        $expectedClassName = ISOCurrency::class;

        try {
            ISOCurrency::validateISO($currency);

            $this->fail("Failed to throw the mismatching exception because the currency is not an ISO currency!");
        } catch (InvalidCurrencyException $exception) {
            $this->assertSame(
                "Provided currency is not type of '$expectedClassName'. '$currencyClassName' provided.",
                $exception->getMessage()
            );
        }

        $this->expectException(InvalidCurrencyException::class);
        $this->expectExceptionMessage(
            "Numerical code of ISO currency MUST be 3 characters long and made of digits only!"
        );

        new ISOCurrency("TEST", "ABC", 0);
    }
}
