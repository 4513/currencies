<?php

declare(strict_types=1);

namespace MiBo\Currencies;

use Generator;

/**
 * Class ListLoader
 *
 * The Loader loads items from a list.
 *
 * @package MiBo\Currencies
 *
 * @see \MiBo\Currencies\ISO\ISOListLoader
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
abstract class ListLoader
{
    /** @var string[]  */
    protected array $resources = [];

    /**
     *  Elements of returned array, or yielded value by generator
     * must be valid information from which a CurrencyInterface
     * can be created.
     *  Element MUST be an object with public properties.
     *
     * @return array<object|\SimpleXMLElement>|\Generator<object|\SimpleXMLElement>
     */
    abstract public function loop(): array|Generator;

    /**
     * @param string $resource
     *
     * @return static
     */
    public function addResource(string $resource): static
    {
        $this->resources[] = $resource;

        return $this;
    }

    /**
     * @param string ...$resources
     *
     * @return static
     */
    public function setResources(string ...$resources): static
    {
        $this->resources = $resources;

        return $this;
    }

    /**
     * @return string[] List of available resources
     */
    final public function getResources(): array
    {
        return $this->resources;
    }
}
