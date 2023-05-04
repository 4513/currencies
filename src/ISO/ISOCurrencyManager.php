<?php

declare(strict_types=1);

namespace MiBo\Currencies\ISO;

use MiBo\Currencies\CurrencyInterface;
use MiBo\Currencies\ISO\Exceptions\InvalidCurrencyException;
use MiBo\Currencies\ISO\Exceptions\ISOCurrencyException;
use Psr\Log\LoggerInterface;

/**
 * Class ISOCurrencyManager
 *
 * Manages ISO-4217.
 *
 * @package MiBo\Currencies
 *
 * @link https://www.iso.org/iso-4217-currency-codes.html
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class ISOCurrencyManager
{
    /** @var \MiBo\Currencies\ISO\ISOCurrencyProvider  */
    private ISOCurrencyProvider $provider;

    /** @var \Psr\Log\LoggerInterface  */
    private LoggerInterface $logger;

    /**
     * @param \MiBo\Currencies\ISO\ISOCurrencyProvider $provider
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(ISOCurrencyProvider $provider, LoggerInterface $logger)
    {
        $this->provider = $provider;
        $this->logger   = $logger;
    }

    /**
     * @param \MiBo\Currencies\CurrencyInterface $currency
     *
     * @return bool Whether the currency's properties format follows the standard
     */
    public function isCurrencyValid(CurrencyInterface $currency): bool
    {
        try {
            ISOCurrency::validateISO($currency);

            $this->getLogger()->debug("Currency '$currency' is valid for ISO.");

            return true;
        } catch (InvalidCurrencyException) {
            $this->getLogger()->debug("Currency '$currency' is not valid for ISO.");

            return false;
        }
    }

    /**
     * @param \MiBo\Currencies\CurrencyInterface $currency
     *
     * @return bool Whether the currency is ISO
     */
    public function isCurrencyISO(CurrencyInterface $currency): bool
    {
        if (!$this->isCurrencyValid($currency)) {
            $this->getLogger()->debug("Currency '$currency' is not ISO standard.");

            return false;
        }

        try {
            $trueCurrency = $this->getProvider()->findByName($currency->getName());

            if ($trueCurrency->is($currency)) {
                $this->getLogger()->debug("Currency '$currency' is ISO standard.");

                return true;
            }

            $this->getLogger()->debug("Currency '$currency' is not ISO standard.");

            return false;
        } catch (ISOCurrencyException) {
            $this->getLogger()->debug("Currency '$currency' is not ISO standard.");

            return false;
        }
    }

    /**
     * @return \MiBo\Currencies\ISO\ISOCurrencyProvider
     */
    public function getProvider(): ISOCurrencyProvider
    {
        return $this->provider;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
