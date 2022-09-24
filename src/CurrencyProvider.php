<?php

namespace MiBo\Currencies;

use MiBo\Currencies\ISO\ISOCurrencyProvider;
use Psr\Log\LoggerInterface;

/**
 * Class CurrencyProvider
 *
 * Provides a currency user is looking for.
 *
 * @package MiBo\Currencies
 *
 * @see ISOCurrencyProvider
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 */
abstract class CurrencyProvider
{
    /** @var ListLoader  */
    protected ListLoader $loader;

    /** @var LoggerInterface  */
    private LoggerInterface $logger;

    /**
     * @param \MiBo\Currencies\ListLoader $loader
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(ListLoader $loader, LoggerInterface $logger)
    {
        $this->loader = $loader;
        $this->logger = $logger;
    }

    /**
     * Finds currency by its name.
     *
     * @param non-empty-string $name Currency name
     *
     * @return CurrencyInterface
     */
    abstract public function findByName(string $name): CurrencyInterface;

    /**
     * Finds currency by its alphabetical code.
     *
     * @param non-empty-string $code Alphabetical currency code
     *
     * @return CurrencyInterface
     */
    abstract public function findByAlphabeticalCode(string $code): CurrencyInterface;

    /**
     * Finds currency by its numerical code.
     *
     * @param non-empty-string $code
     *
     * @return CurrencyInterface
     */
    abstract public function findByNumericalCode(string $code): CurrencyInterface;

    /**
     * Finds currencies by a country they are used in.
     *
     * @param non-empty-string $country Name of country
     *
     * @return CurrencyInterface[]
     */
    abstract public function findByCountry(string $country): array;

    /**
     * @param \MiBo\Currencies\ListLoader $loader
     *
     * @return static
     */
    final public function setLoader(ListLoader $loader): static
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * @return ListLoader
     */
    final public function getLoader(): ListLoader
    {
        return $this->loader;
    }

    /**
     * @return LoggerInterface
     */
    final protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
