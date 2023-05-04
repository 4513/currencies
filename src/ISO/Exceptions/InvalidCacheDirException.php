<?php

declare(strict_types=1);

namespace MiBo\Currencies\ISO\Exceptions;

use RuntimeException;

/**
 * Class InvalidCacheDirException
 *
 * Exception is thrown when provided Cache Directory does not exist.
 *
 * @package MiBo\Currencies\ISO\Exceptions
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
class InvalidCacheDirException extends RuntimeException implements ISOCurrencyException
{
}
