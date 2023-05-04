<?php

declare(strict_types=1);

namespace MiBo\Currencies;

use Stringable;

/**
 * Interface CurrencyInterface
 *
 * Implements common function for currencies.
 * CurrencyInterface SHOULD be created only via its CurrencyProvider.
 *
 * @package MiBo\Currencies
 *
 * @see \MiBo\Currencies\ISO\ISOCurrency
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
interface CurrencyInterface extends Stringable
{
    /**
     * @return non-empty-string Name of the currency.
     */
    public function getName(): string;

    /**
     * @return non-empty-string Alphabetical code of the currency.
     */
    public function getAlphabeticalCode(): string;

    /**
     * @return numeric-string Numerical code of the currency.
     */
    public function getNumericalCode(): string;

    /**
     * @return int|null Count of decimals for the currency.
     *       If not applicable, null is returned.
     */
    public function getMinorUnitRate(): ?int;

    /**
     * @param \MiBo\Currencies\CurrencyInterface $currency
     *
     * @return bool Whether currency matches the provided currency.
     */
    public function is(CurrencyInterface $currency): bool;
}
