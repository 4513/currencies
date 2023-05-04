<?php

declare(strict_types=1);

namespace MiBo\Currencies\ISO\Exceptions;

use MiBo\Currencies\Exceptions\CurrencyException;

/**
 * Interface ISOCurrencyException
 *
 * The Exception covers all within-library ISO exceptions.
 *
 * @package MiBo\Currencies\ISO\Exceptions
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
interface ISOCurrencyException extends CurrencyException
{
}
