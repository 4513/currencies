<?php

namespace MiBo\Currencies\ISO\Exceptions;

use RuntimeException;

/**
 * Class InvalidCacheDirException
 *
 * Exception is thrown when provided Cache Directory does not exist.
 *
 * @package MiBo\Currencies\ISO\Exceptions
 *
 * @author Michal Boris <michal.boris@gmail.com>
 */
class InvalidCacheDirException extends RuntimeException implements ISOCurrencyException
{
}
