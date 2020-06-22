<?php

namespace Lib\Reader;

use \SplFileObject;
use \InvalidArgumentException;

class CSVReader
{
    private const DEFAULT_CHUNK_SIZE = 2;

    /**
     * @var SplFileObject
     */
    private SplFileObject $file;

    /**
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        if (!file_exists($fileName)) {
            throw new InvalidArgumentException('Invalid file name');
        }
        $this->file = new SplFileObject($fileName, 'r');
    }

    /**
     * @param int $chunkSize
     *
     * @return iterable
     */
    public function parseChunked(int $chunkSize = self::DEFAULT_CHUNK_SIZE): iterable
    {
        $result = [];

        for (;;) {
            $line = $this->file->fgetcsv();
            if (!$this->file->valid()) {
                break;
            }
            $result[] = $line;
            if ($chunkSize === count($result)) {
                break;
            }
        }

        return $result;
    }
}
