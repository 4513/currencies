<?php

declare(strict_types=1);

namespace MiBo\Currencies\ISO\Contracts;

use DOMDocument;
use Generator;
use MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException;
use XMLReader;

/**
 * Trait LoopingTrait
 *
 * @package MiBo\Currencies\ISO\Contracts
 *
 * @since 0.3
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
trait LoopingTrait
{
    /**
     * @param string $resource
     * @param string $entityTag
     *
     * @return \Generator
     * @throws \MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException
     */
    public function loop(string $resource, string $entityTag): Generator
    {
        $xmlReader = XMLReader::open($resource);

        // The file is not available, however we can do nothing about that. Cannot be covered.
        if (!$xmlReader instanceof XMLReader) {
            // @codeCoverageIgnoreStart
            throw new UnavailableCurrencyListException(
                strtr("Failed to open currency list '%list%'", ["%list%" => $resource])
            );
            // @codeCoverageIgnoreEnd
        }

        $domDoc = new DOMDocument();

        // @phpcs:disable
        while ($xmlReader->read() && $xmlReader->name !== $entityTag) {
            // Reads the file until it finds the first currency
        }
        // @phpcs:enable

        while ($xmlReader->name === $entityTag) {
            $DOMNode = $xmlReader->expand();

            // The list is not valid, and we cannot do anything about that. Cannot be covered.
            if ($DOMNode === false) {
                // @codeCoverageIgnoreStart
                throw new UnavailableCurrencyListException(
                    strtr("Failed to read currency list '%list%'", ["%list%" => $resource])
                );
                // @codeCoverageIgnoreEnd
            }

            $xml = $domDoc->importNode($DOMNode, true);

            $item = simplexml_import_dom($xml);

            // The list is not valid, and we cannot do anything about that. Cannot be covered.
            // @codeCoverageIgnoreStart
            if ($item === null) {
                throw new UnavailableCurrencyListException(
                    strtr("Failed to read currency list '%list%'", ["%list%" => $resource])
                );
            }

            // @codeCoverageIgnoreEnd

            yield $item;

            $xmlReader->next($entityTag); // @codeCoverageIgnore
        }
    }
}
