<?php


namespace App\Api\MoveImportFile;


use Lib\App\KernelInterface;

class Handler
{
    private const DEFAULT_DIR = '/var/tmp';

    /**
     * @var string
     */
    private string $projectDir;

    /**
     * Handler constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->projectDir = $kernel->getProjectDir();
    }

    /**
     * @param Command $cmd
     * @return string|null
     */
    public function __invoke(Command $cmd): string
    {
        $path = $this->projectDir.self::DEFAULT_DIR;
        if (file_exists($path) && !is_dir($path)) {
            unlink($path);
        }
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $fileName = $path.'/'.$this->generateFileName();

        if (!move_uploaded_file($cmd->getFileName(), $fileName)) {
            throw new \Exception('File is not uploadable');
        }

        return $fileName;
    }

    private function generateFileName(): string
    {
        return (string)time();
    }
}