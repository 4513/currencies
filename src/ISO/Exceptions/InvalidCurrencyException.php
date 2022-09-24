<?php

namespace MiBo\Currencies\ISO\Exceptions;

use OutOfBoundsException;

/**
 * Class InvalidCurrencyException
 *
 * The Exception is thrown when trying to create/load non-ISO currency.
 *
 * @package MiBo\Currencies\ISO\Exceptions
 *
 * @author Michal Boris <michal.boris@gmail.com>
 */
final class InvalidCurrencyException extends OutOfBoundsException implements ISOCurrencyException
{
}
