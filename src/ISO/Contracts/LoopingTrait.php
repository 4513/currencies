<?php

namespace MiBo\Currencies\ISO\Contracts;

use Generator;
use MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException;

/**
 * Trait LoopingTrait
 *
 * @package MiBo\Currencies\ISO\Contracts
 *
 * @since 0.3
 *
 * @author Michal Boris <michal.boris@gmail.com>
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
        $xmlReader = \XMLReader::open($resource);

        if (!$xmlReader instanceof \XMLReader) {
            throw new UnavailableCurrencyListException(
                strtr("Failed to open currency list '%list%'", ["%list%" => $resource])
            );
        }

        $domDoc = new \DOMDocument();

        // @phpcs:disable
        while ($xmlReader->read() && $xmlReader->name !== $entityTag) {
            // Reads the file until it finds the first currency
        }
        // @phpcs:enable

        while ($xmlReader->name === $entityTag) {
            $DOMNode = $xmlReader->expand();

            if ($DOMNode === false) {
                throw new UnavailableCurrencyListException(
                    strtr("Failed to read currency list '%list%'", ["%list%" => $resource])
                );
            }

            $xml = $domDoc->importNode($DOMNode, true);

            $item = simplexml_import_dom($xml);

            if ($item === null) {
                throw new UnavailableCurrencyListException(
                    strtr("Failed to read currency list '%list%'", ["%list%" => $resource])
                );
            }

            yield $item;

            $xmlReader->next($entityTag);
        }
    }
}
