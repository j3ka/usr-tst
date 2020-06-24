<?php

namespace App\Api\ImportUsersCSV;

use App\Factory\UserFactory;
use App\Form\UserForm;
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
            $usersData = $reader->parseChunked($cmd->getChunkSize());
            if (empty($usersData)) {
                break;
            }
            foreach ($usersData as $userData) {
                $form = new UserForm();
                $form->validate([
                    'id' => $userData[0] ?? '',
                    'username' => $userData[1] ?? '',
                    'email' => $userData[2] ?? '',
                    'currency' => $userData[3] ?? '',
                    'total' => $userData[4] ?? '',
                ]);
                if (!$form->isValid()) {
                    continue;
                }
                $user = $this->userFactory->arrayToEntity($form->getClearedData());

                $this->sess->add($user);
            }
            $this->sess->commit();
        }

        $this->cache->clearKeys();
        if (file_exists($cmd->getFileName())) {
            unlink($cmd->getFileName());
        }
    }
}
