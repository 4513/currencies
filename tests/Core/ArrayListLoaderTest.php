<?php

declare(strict_types=1);

namespace MiBo\Currencies\Tests;

use MiBo\Currencies\ISO\ISOArrayListLoader;
use MiBo\Currencies\ISO\ISOListLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayListLoaderTest
 *
 * @package MiBo\Currencies\Tests
 *
 * @author Michal Boris <michal.boris27@gmail.com>
 *
 * @since 1.2
 *
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise.
 */
#[CoversClass(ISOArrayListLoader::class)]
#[Small]
class ArrayListLoaderTest extends TestCase
{
    public function test(): void
    {
        $loader = new ISOArrayListLoader();

        foreach ($loader->loop() as $currency) {
            self::assertIsObject($currency);
            self::assertSame("AFGHANISTAN", $currency->CtryNm);
            self::assertSame("Afghani", $currency->CcyNm);
            self::assertSame("AFN", $currency->Ccy);
            self::assertSame("971", $currency->CcyNbr);
            self::assertSame("2", $currency->CcyMnrUnts);

            break;
        }
    }

    public function create(): void
    {
        self::expectNotToPerformAssertions();

        return;

        if (file_exists(__DIR__ . "/tmp.php")) {
            unlink(__DIR__ . "/tmp.php");
        }

        touch(__DIR__ . "/tmp.php");

        $a      = "<?php\n\nreturn [\n    ";
        $loader = new ISOListLoader(ISOListLoader::SOURCE_WEB);

        foreach ($loader->loop() as $currency) {
            $currency->CtryNm = addslashes((string) $currency->CtryNm);
            $currency->CcyNm  = addslashes((string) $currency->CcyNm);
            $a               .= "[\n        'CtryNm'     => '{$currency->CtryNm}',\n";
            $a               .= "        'CcyNm'      => '{$currency->CcyNm}',\n";
            $a               .= "        'Ccy'        => '{$currency->Ccy}',\n";
            $a               .= "        'CcyNbr'     => '{$currency->CcyNbr}',\n";
            $a               .= "        'CcyMnrUnts' => '{$currency->CcyMnrUnts}',\n    ],\n    ";
        }

        $a .= "];\n";

        file_put_contents(__DIR__ . "/tmp.php", $a);
    }
}
