<?php

declare(strict_types=1);

namespace MiBo\Currencies;
use Psr\Log\LoggerInterface;

/**
 * Class CurrencyProvider
 *
 * Provides a currency user is looking for.
 *
 * @package MiBo\Currencies
 *
 * @see \MiBo\Currencies\ISO\ISOCurrencyProvider
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
abstract class CurrencyProvider
{
    /** @var \MiBo\Currencies\ListLoader  */
    protected ListLoader $loader;

    /** @var \Psr\Log\LoggerInterface  */
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
     * @return \MiBo\Currencies\CurrencyInterface
     */
    abstract public function findByName(string $name): CurrencyInterface;

    /**
     * Finds currency by its alphabetical code.
     *
     * @param non-empty-string $code Alphabetical currency code
     *
     * @return \MiBo\Currencies\CurrencyInterface
     */
    abstract public function findByAlphabeticalCode(string $code): CurrencyInterface;

    /**
     * Finds currency by its numerical code.
     *
     * @param non-empty-string $code
     *
     * @return \MiBo\Currencies\CurrencyInterface
     */
    abstract public function findByNumericalCode(string $code): CurrencyInterface;

    /**
     * Finds currencies by a country they are used in.
     *
     * @param non-empty-string $country Name of country
     *
     * @return \MiBo\Currencies\CurrencyInterface[]
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
     * @return \MiBo\Currencies\ListLoader
     */
    final public function getLoader(): ListLoader
    {
        return $this->loader;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    final protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
