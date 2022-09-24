<?php

namespace MiBo\Currencies\ISO;

use Generator;
use MiBo\Currencies\ISO\Contracts\LoopingTrait;
use MiBo\Currencies\ISO\Exceptions\InvalidCacheDirException;
use MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException;
use MiBo\Currencies\ListLoader;
use SimpleXMLElement;

/**
 * Class ISOLocalListLoader
 *
 * @package MiBo\Currencies\ISO
 *
 * @since 0.3
 *
 * @author Michal Boris <michal.boris@gmail.com>
 */
class ISOLocalListLoader extends ListLoader
{
    use LoopingTrait {
        LoopingTrait::loop as contractLoop;
    }

    public const FILE_NAME = "ISO_4217.xml";

    protected string $cacheDir;
    protected ?ISOListLoader $loader = null;

    /**
     * @param string $cacheDir
     *
     * @throws \MiBo\Currencies\ISO\Exceptions\InvalidCacheDirException
     */
    public function __construct(string $cacheDir)
    {
        if (!is_dir($cacheDir)) {
            throw new InvalidCacheDirException("Directory '$cacheDir' does not exist!");
        }

        $this->cacheDir = $cacheDir;

        if ($this->isFileCached()) {
            $this->resources = [$cacheDir . DIRECTORY_SEPARATOR . self::FILE_NAME];
        }
    }

    /**
     * @return bool True on success, false on failure.
     */
    final public function updateFile(): bool
    {
        $handle = curl_init(ISOListLoader::SOURCE_WEB);

        if ($handle === false) {
            return false;
        }

        $local = fopen($this->getCacheDir() . DIRECTORY_SEPARATOR . self::FILE_NAME, "wb");

        if ($local === false) {
            return false;
        }

        curl_setopt($handle, CURLOPT_FILE, $local);
        curl_setopt($handle, CURLOPT_HEADER, 0);

        $success = curl_exec($handle);
        curl_close($handle);

        fclose($local);

        if ($success) {
            $this->resources = [$this->getCacheDir() . DIRECTORY_SEPARATOR . self::FILE_NAME];
        }

        return (bool) $success;
    }

    /**
     * @return bool
     */
    protected function isFileCached(): bool
    {
        return file_exists($this->getCacheDir() . DIRECTORY_SEPARATOR . self::FILE_NAME);
    }

    /**
     * @return Generator<SimpleXMLElement> object which properties are set same way as in
     *      the resource file.
     *
     * @inheritdoc
     */
    public function loop(): Generator
    {
        if ($this->loader !== null || (!$this->isFileCached() && !$this->updateFile())) {
            if ($this->loader === null) {
                $this->loader = new ISOListLoader(ISOListLoader::SOURCE_WEB);
            }

            try {
                foreach ($this->loader->loop() as $item) {
                    yield $item;
                }
            } catch (Exceptions\UnavailableCurrencyListException $e) {
                if ($this->loader->getResources()[0] === ISOListLoader::SOURCE_LOCAL) {
                    throw $e;
                }

                $this->loader->setResources(ISOListLoader::SOURCE_LOCAL);

                foreach ($this->loader->loop() as $item) {
                    yield $item;
                }
            }
        } else {
            /** @var SimpleXMLElement $item */
            foreach ($this->contractLoop(
                $this->getResources()[0],
                ISOListLoader::SHORT_CURRENCY_ENTITY
            ) as $item) {
                yield $item;
            }
        }
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return $this->cacheDir;
    }

    /**
     * @param string $cacheDir
     */
    public function setCacheDir(string $cacheDir): void
    {
        $this->cacheDir = $cacheDir;
    }

    /**
     * @inheritdoc
     *
     * @throws \MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException
     */
    public function setResources(string ...$resources): static
    {
        throw new UnavailableCurrencyListException("Cannot use custom list of ISO currencies!");
    }

    /**
     * @inheritdoc
     *
     * @throws \MiBo\Currencies\ISO\Exceptions\UnavailableCurrencyListException
     */
    public function addResource(string $resource): static
    {
        throw new UnavailableCurrencyListException("Cannot use custom list of ISO currencies!");
    }
}
