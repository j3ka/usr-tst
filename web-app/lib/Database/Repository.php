<?php

namespace Lib\Database;

use Lib\Database\Session;

abstract class Repository
{
    /**
     * @var Session
     */
    protected Session $sess;

    /**
     * @var EntityFactoryInterface
     */
    protected EntityFactoryInterface $factory;

    /**
     * @param Session $sess
     */
    public function __construct(Session $sess, EntityFactoryInterface $factory)
    {
        $this->sess = $sess;
        $this->factory = $factory;
    }

    abstract public function getEntityClass(): string;

    public function findBy(array $criteria): array
    {
        $arrayData = $this->sess
                          ->query($this->getEntityClass())
                          ->filterBy($criteria)
                          ->all();

        return $this->factory->arrayToEntityMultiple($arrayData);
    }
}
