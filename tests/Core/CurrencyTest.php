<?php

namespace MiBo\Currencies\Tests;

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
}
