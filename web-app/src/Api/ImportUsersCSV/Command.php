<?php 

namespace App\Api\ImportUsersCSV;

class Command
{
    private const DEFAULT_CHUNK_SIZE = 100;
    /**
     * @var string
     */
    private string $fileName;

    /**
     * @var int
     */
    private int $chunkSize;

    /**
     * @param string $fileName
     */
    public function __construct(string $fileName, int $chunkSize = self::DEFAULT_CHUNK_SIZE)
    {
        $this->fileName = $fileName;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return int
     */
    public function getChunkSize(): int
    {
        return $this->chunkSize;
    }
}
