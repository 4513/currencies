<?php

namespace MiBo\Currencies\ISO\Exceptions;

use RuntimeException;

/**
 * Class UnavailableCurrencyListException
 *
 * The Exception is thrown, when invalid, unavailable, or unreachable currency list is set up.
 *
 * @package MiBo\Currencies\ISO\Exceptions
 *
 * @author Michal Boris <michal.boris@gmail.com>
 */
final class UnavailableCurrencyListException extends RuntimeException implements ISOCurrencyException
{
}
