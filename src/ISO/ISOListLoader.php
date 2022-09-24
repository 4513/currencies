<?php

namespace MiBo\Currencies\ISO;

use Generator;
use MiBo\Currencies\ISO\Contracts\LoopingTrait;
use MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException;
use MiBo\Currencies\ListLoader;
use SimpleXMLElement;

/**
 * Class ISOListLoader
 *
 * Provides source of list of ISO-4217 currencies.
 *
 * @package MiBo\Currencies
 *
 * @link https://www.iso.org/iso-4217-currency-codes.html
 * @link https://www.six-group.com/dam/download/financial-information/data-center/iso-currrency/lists/list-one.xml
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 */
final class ISOListLoader extends ListLoader
{
    use LoopingTrait {
        LoopingTrait::loop as contractLoop;
    }

    /**
     * List key word of currency entity.
     *
     * @internal
     * @deprecated Valid only for this package.
     */
    public const SHORT_CURRENCY_ENTITY = "CcyNtry";

    /**
     * List key word of country name.
     *
     * @internal
     * @deprecated Valid only for this package.
     */
    public const SHORT_COUNTRY_NAME = "CtryNm";

    /**
     * List key word of currency name.
     *
     * @internal
     * @deprecated Valid only for this package.
     */
    public const SHORT_CURRENCY_NAME = "CcyNm";

    /**
     * List key word of currency alphabetical code.
     *
     * @internal
     * @deprecated Valid only for this package.
     */
    public const SHORT_CURRENCY = "Ccy";

    /**
     * List key word of currency numerical code.
     *
     * @internal
     * @deprecated Valid only for this package.
     */
    public const SHORT_CURRENCY_NUMBER = "CcyNbr";

    /**
     * List key word of currency minor units.
     *
     * @internal
     * @deprecated Valid only for this package.
     */
    public const SHORT_CURRENCY_MINOR_UNITS = "CcyMnrUnts";

    /**
     * Uses online list of currencies.
     * The list should be always updated.
     */
    // @phpcs:ignore
    public const SOURCE_WEB = "https://www.six-group.com/dam/download/" .
        "financial-information/data-center/iso-currrency/lists/list-one.xml";

    /**
     * Uses package's list of currencies.
     *  The list might be outdated however if a change within
     * the list is made, new patch version of this package is
     * released.
     */
    public const SOURCE_LOCAL = __DIR__ . DIRECTORY_SEPARATOR . "resources/ISO_4217.xml";

    /**
     * @param self::SOURCE_* $source
     *
     * @throws UnavailableCurrencyListException If the source is not valid.
     */
    public function __construct(string $source = self::SOURCE_LOCAL)
    {
        $this->addResource($source);
    }

    /**
     * @return Generator<SimpleXMLElement> object which properties are set same way as in
     *      the resource file.
     *
     * @throws UnavailableCurrencyListException If failed to read resources.
     */
    public function loop(): Generator
    {
        /** @var SimpleXMLElement $item */
        foreach ($this->contractLoop($this->getResources()[0], self::SHORT_CURRENCY_ENTITY) as $item) {
            yield $item;
        }
    }

    /**
     * Changes the resource.
     *
     * @param string $resource
     *
     * @return static
     *
     * @throws UnavailableCurrencyListException
     */
    public function addResource(string $resource): static
    {
        if (!in_array($resource, [self::SOURCE_LOCAL, self::SOURCE_WEB])) {
            throw new UnavailableCurrencyListException("Cannot use custom list of ISO currencies!");
        }

        $this->resources = [$resource];

        return $this;
    }

    /**
     * Changes the resource
     *
     * @param string ...$resources
     *
     * @return static
     *
     * @throws UnavailableCurrencyListException
     */
    public function setResources(string ...$resources): static
    {
        foreach ($resources as $resource) {
            $this->addResource($resource);
        }

        return $this;
    }
}
