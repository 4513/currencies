<?php

namespace MiBo\Currencies\ISO;

use MiBo\Currencies\CurrencyInterface;
use MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException;

/**
 * Class Currency
 *
 * Properties are filled based on ISO-4217.
 *
 * @package MiBo\Currencies
 *
 * @link https://www.iso.org/iso-4217-currency-codes.html
 *
 * @internal ISOCurrency MUST be created only via ISOCurrencyProvider
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 */
final class ISOCurrency implements CurrencyInterface
{
    /**
     * @var non-empty-string Official name of the currency (in English)
     *
     * @example "Euro"|"Czech Koruna"|"US Dollar"
     */
    private string $name;

    /**
     * @var non-empty-string 3 capitalized letters
     *
     * @example "EUR"|"CZK"|"USD"
     */
    private string $alphabeticalCode;

    /**
     * @var numeric-string 3-digit code (Might starts with zero)
     *
     * @example "978"|"203"|"840"
     */
    private string $numericalCode;

    /**
     * @var int|null Shows the decimal relationship between minor unit
     *      and the currency itself.
     *       N.A. is saved as null. (gold, silver, ...)
     */
    private ?int $minorUnitRate;

    /**
     * @param non-empty-string $name
     * @param non-empty-string $alphabeticCode
     * @param numeric-string $numericalCode
     * @param int|null $minorUnitRate
     *
     * @internal ISOCurrency MUST be created only via ISOCurrencyProvider
     *
     * @throws InvalidCurrencyException
     * @see validateISO
     */
    public function __construct(
        string $name,
        string $alphabeticCode,
        string $numericalCode,
        ?int $minorUnitRate = null
    )
    {
        $this->name             = $name;
        $this->alphabeticalCode = $alphabeticCode;
        $this->numericalCode    = $numericalCode;
        $this->minorUnitRate    = $minorUnitRate;

        self::validateISO($this);
    }

    /**
     * Validates whether the currency is properly set.
     *
     * @param CurrencyInterface $currency Currency to be validated.
     *
     * @return void
     *
     * @throws InvalidCurrencyException On invalid data.
     */
    public static function validateISO(CurrencyInterface $currency): void
    {
        if (!$currency instanceof ISOCurrency) {
            throw new InvalidCurrencyException(
                strtr("Provided currency is not type of '%correct%'. '%provided%' provided.", [
                    "%correct%"  => self::class,
                    "%provided%" => get_class($currency),
                ])
            );
        }

        if (!preg_match("/^[A-Z]{3}$/", $currency->getAlphabeticalCode())) {
            throw new InvalidCurrencyException(
                "Alphabetical code of ISO currency MUST be 3 characters long and made of capitals only!"
            );
        }

        if (!preg_match("/^[0-9]{3}$/", $currency->getNumericalCode())) {
            throw new InvalidCurrencyException(
                "Numerical code of ISO currency MUST be 3 characters long and made of digits only!"
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getAlphabeticalCode(): string
    {
        return $this->alphabeticalCode;
    }

    /**
     * @inheritDoc
     */
    public function getNumericalCode(): string
    {
        return $this->numericalCode;
    }

    /**
     * @inheritDoc
     */
    public function getMinorUnitRate(): ?int
    {
        return $this->minorUnitRate;
    }

    /**
     * @inheritDoc
     */
    public function is(CurrencyInterface $currency): bool
    {
        return $this->getAlphabeticalCode() === $currency->getAlphabeticalCode() &&
            $this->getNumericalCode() === $currency->getNumericalCode() &&
            $this->getName() === $currency->getName() &&
            $this->getMinorUnitRate() === $currency->getMinorUnitRate();
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getAlphabeticalCode();
    }
}
