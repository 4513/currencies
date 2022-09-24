<?php

namespace MiBo\Currencies\ISO\Exceptions;

use RuntimeException;

/**
 * Class NoUniversalCurrencyException
 *
 * The Exception is thrown, when a non-universal currency is about to load.
 *
 * @package MiBo\Currencies\ISO\Exceptions
 *
 * @author Michal Boris <michal.boris@gmail.com>
 */
final class NoUniversalCurrencyException extends RuntimeException implements ISOCurrencyException
{
}
