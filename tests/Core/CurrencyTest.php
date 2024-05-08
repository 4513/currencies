<?php

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\CurrencyInterface;
use MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException;
use MiBo\Currencies\ISO\ISOCurrency;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * Class CurrencyTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 */
#[CoversClass(ISOCurrency::class)]
#[Small]
class CurrencyTest extends TestCase
{
    public function testCreateValidCurrency(): void
    {
        $currency = new ISOCurrency(
            "Test",
            "ABC",
            "012",
            null
        );

        self::assertSame("ABC", (string) $currency);

        self::assertSame("Test", $currency->getName());
        self::assertSame("ABC", $currency->getAlphabeticalCode());
        self::assertSame("012", $currency->getNumericalCode());
        self::assertSame(null, $currency->getMinorUnitRate());
    }

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

    public function testIsSame(): void
    {
        $first = new ISOCurrency("TEST", "ABC", "012", null);
        $second = new ISOCurrency("TEST", "ABC", "012", null);
        $third = new ISOCurrency("TEST", "ABC", "123", null);

        self::assertTrue($first->is($first));
        self::assertTrue($first->is($second));

        self::assertFalse($first->is($third));
    }

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
            self::assertSame(
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
