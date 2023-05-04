<?php

declare(strict_types=1);

namespace MiBo\Currencies\ISO;

use MiBo\Currencies\CurrencyProvider;
use MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException;
use MiBo\Currencies\ISO\Exceptions\NoUniversalCurrencyException;
use SimpleXMLElement;
use stdClass;

/**
 * Class ISOCurrencyProvider
 *
 * Provides ISO-4217 currency.
 *
 * @package MiBo\Currencies
 *
 * @link https://www.iso.org/iso-4217-currency-codes.html
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class ISOCurrencyProvider extends CurrencyProvider
{
    /**
     * @param non-empty-string $name
     *
     * @return \MiBo\Currencies\ISO\ISOCurrency
     *
     * @throws \MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException If the currency is not valid.
     * @throws \MiBo\Currencies\ISO\Exceptions\NoUniversalCurrencyException If no-universal currency found.
     */
    final public function findByName(string $name): ISOCurrency
    {
        $this->getLogger()->debug("Looking for a currency with name '$name'");

        return $this->findFirstBy(ISOListLoader::SHORT_CURRENCY_NAME, $name);
    }

    /**
     * @param non-empty-string $code
     *
     * @return \MiBo\Currencies\ISO\ISOCurrency
     *
     * @throws \MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException If the currency is not valid.
     * @throws \MiBo\Currencies\ISO\Exceptions\NoUniversalCurrencyException If no-universal currency found.
     */
    final public function findByAlphabeticalCode(string $code): ISOCurrency
    {
        $this->getLogger()->debug("Looking for a currency with alphabetical code '$code'");

        return $this->findFirstBy(ISOListLoader::SHORT_CURRENCY, $code);
    }

    /**
     * @param numeric-string $code
     *
     * @return \MiBo\Currencies\ISO\ISOCurrency
     *
     * @throws \MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException If the currency is not valid.
     * @throws \MiBo\Currencies\ISO\Exceptions\NoUniversalCurrencyException If no-universal currency found.
     */
    final public function findByNumericalCode(string $code): ISOCurrency
    {
        $this->getLogger()->debug("Looking for a currency with numerical code '$code'");

        return $this->findFirstBy(ISOListLoader::SHORT_CURRENCY_NUMBER, $code);
    }

    /**
     * @param non-empty-string $country
     *
     * @return \MiBo\Currencies\ISO\ISOCurrency[]
     *
     * @throws \MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException If the currency is not valid.
     * @throws \MiBo\Currencies\ISO\Exceptions\NoUniversalCurrencyException If no-universal currency found.
     */
    final public function findByCountry(string $country): array
    {
        $this->getLogger()->debug("Looking for a currencies used in country '$country'");

        return $this->findBy(ISOListLoader::SHORT_COUNTRY_NAME, $country);
    }

    /**
     * @param \MiBo\Currencies\ISO\ISOListLoader::SHORT_* $key
     * @param non-empty-string|numeric-string $needle
     * @param int $maxMatches Count of maximum returned items.
     *           0 - returns all found items (default)
     *
     * @return \MiBo\Currencies\ISO\ISOCurrency[]
     *
     * @throws \MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException If the currency is not valid.
     * @throws \MiBo\Currencies\ISO\Exceptions\NoUniversalCurrencyException If no-universal currency found.
     *
     * @internal This function might be overridden to implement cache.
     */
    protected function findBy(string $key, string $needle, int $maxMatches = 0): array
    {
        $list = [];

        $matches = 0;

        // @phpcs:ignore
        /** @var \stdClass $item */
        foreach ($this->getLoader()->loop() as $item) {
            $this->getLogger()->debug("Checking currency '{$item->{ISOListLoader::SHORT_CURRENCY_NAME}}'");

            if (isset($item->$key) && $item->$key == $needle) {
                $this->getLogger()->debug("Found currency '{$item->{ISOListLoader::SHORT_CURRENCY_NAME}}'");

                $matches++;

                $list[] = $this->transformToCurrency($item);

                if ($matches === $maxMatches) {
                    return $list;
                }
            }
        }

        if (!empty($list)) {
            return $list;
        }

        $this->getLogger()->info("Failed to find currency by '$key' matching '$needle'");

        throw new InvalidCurrencyException("The ISO currency could not be found!");
    }

    /**
     * @internal Returns first element of found matches
     *
     * @see findBy
     *
     * @param \MiBo\Currencies\ISO\ISOListLoader::SHORT_* $key
     * @param non-empty-string|numeric-string $needle
     *
     * @return \MiBo\Currencies\ISO\ISOCurrency
     *
     * @throws \MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException If the currency is not valid.
     * @throws \MiBo\Currencies\ISO\Exceptions\NoUniversalCurrencyException If no-universal currency found.
     */
    private function findFirstBy(string $key, string $needle): ISOCurrency
    {
        $match = $this->findBy($key, $needle, 1);
        $match = reset($match);

        if ($match === false) {
            throw new InvalidCurrencyException("The ISO currency could not be found!"); // @codeCoverageIgnore
        }

        return $match;
    }

    /**
     * @param \SimpleXMLElement|\stdClass $element
     *
     * @return \MiBo\Currencies\ISO\ISOCurrency
     *
     * @throws \MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException If the currency is not valid.
     * @throws \MiBo\Currencies\ISO\Exceptions\NoUniversalCurrencyException If no-universal currency found.
     */
    final protected function transformToCurrency(SimpleXMLElement|stdClass $element): ISOCurrency
    {
        if ($element->{ISOListLoader::SHORT_CURRENCY_NAME} == "No universal currency") {
            throw new NoUniversalCurrencyException("No universal currency!");
        }

        $minorRate = null;

        if (((string) $element->{ISOListLoader::SHORT_CURRENCY_MINOR_UNITS}) !== "N.A.") {
            $minorRate = intval($element->{ISOListLoader::SHORT_CURRENCY_MINOR_UNITS});
        }

        // @phpcs:disable
        return new ISOCurrency(
            /** @phpstan-ignore-next-line */
            (string) $element->{ISOListLoader::SHORT_CURRENCY_NAME},
            /** @phpstan-ignore-next-line */
            (string) $element->{ISOListLoader::SHORT_CURRENCY},
            /** @phpstan-ignore-next-line */
            (string) $element->{ISOListLoader::SHORT_CURRENCY_NUMBER},
            $minorRate
        );
        // @phpcs:enable
    }
}
