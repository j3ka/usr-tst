<?php

namespace App\Api\ImportUsersCSV;

use App\Factory\UserFactory;
use Lib\App\CacheInterface;
use Lib\Database\Session;
use Lib\Reader\CSVReader;

class Handler
{
    /**
     * @var UserFactory
     */
    private UserFactory $userFactory;

    /**
     * @var Session
     */
    private Session $sess;

    /**
     * @var CacheInterface
     */
    private CacheInterface $cache;

    /**
     * @param UserFactory $factory
     * @param Session $sess
     * @param CacheInterface $cache
     */
    public function __construct(UserFactory $factory, Session $sess, CacheInterface $cache)
    {
        $this->userFactory = $factory;
        $this->sess = $sess;
        $this->cache = $cache;
    }

    /**
     * @param Command $cmd
     */
    public function __invoke(Command $cmd)
    {
        $reader = new CSVReader($cmd->getFileName());
        for (;;) {
            $userData = $reader->parseChunked($cmd->getChunkSize());
            if (empty($userData)) {
                break;
            }
            foreach ($userData as $userData) {
                // TODO: form validation
                $user = $this->userFactory->create(
                    (int) $userData[0],
                    $userData[1],
                    $userData[2],
                    $userData[3],
                    (float) $userData[4]
                );
                $this->sess->add($user);
            }
            $this->sess->commit();
        }

        $this->cache->clearKeys();
    }
}
