<?php


namespace App\Queue\UserImport;


use Lib\Queue\QueueMessageInterface;

class Message implements QueueMessageInterface
{
    /**
     * @var string
     */
    private string $fileName;

    /**
     * Message constructor.
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return mixed|string[]
     */
    public function jsonSerialize()
    {
        return [
            'fileName' => $this->fileName,
        ];
    }
}