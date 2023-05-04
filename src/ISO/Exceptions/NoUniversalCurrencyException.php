<?php

declare(strict_types=1);

namespace MiBo\Currencies\ISO\Exceptions;

use RuntimeException;

/**
 * Class NoUniversalCurrencyException
 *
 * The Exception is thrown, when a non-universal currency is about to load.
 *
 * @package MiBo\Currencies\ISO\Exceptions
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
final class NoUniversalCurrencyException extends RuntimeException implements ISOCurrencyException
{
}
