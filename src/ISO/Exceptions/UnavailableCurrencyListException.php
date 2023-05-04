<?php

declare(strict_types=1);

namespace MiBo\Currencies\ISO\Exceptions;

use RuntimeException;

/**
 * Class UnavailableCurrencyListException
 *
 * The Exception is thrown, when invalid, unavailable, or unreachable currency list is set up.
 *
 * @package MiBo\Currencies\ISO\Exceptions
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class UnavailableCurrencyListException extends RuntimeException implements ISOCurrencyException
{
}
