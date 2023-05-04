<?php

declare(strict_types=1);

namespace MiBo\Currencies\ISO\Exceptions;

use OutOfBoundsException;

/**
 * Class InvalidCurrencyException
 *
 * The Exception is thrown when trying to create/load non-ISO currency.
 *
 * @package MiBo\Currencies\ISO\Exceptions
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class InvalidCurrencyException extends OutOfBoundsException implements ISOCurrencyException
{
}
