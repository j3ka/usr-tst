<?php

namespace Lib\Database;

use \InvalidArgumentException;

class Session
{
    /**
     * @var \PDO
     */
    private \PDO $pdo;

    /**
     * @var EntityFieldsResolver
     */
    private EntityFieldsResolver $entityResolver;

    /**
     * @var bool
     */
    private bool $isTransactionStarted;

    /**
     * @param \PDO $pdo
     * @param EntityFieldsResolver $entityResolver
     */
    public function __construct(\PDO $pdo, EntityFieldsResolver $entityResolver)
    {
        $this->pdo = $pdo;
        $this->entityResolver = $entityResolver;
        $this->isTransactionStarted = false;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return self
     */
    public function add(EntityInterface $entity): self
    {
        if (false === $this->isTransactionStarted) {
            $this->pdo->beginTransaction();
            $this->isTransactionStarted = true;
        }

        $tableName = $entity->getTableName();
        if (empty($tableName)) {
            throw new InvalidArgumentException('Table for '.get_class($entity).' is not defined');
        }

        $valueMap = $this->entityResolver->getFieldsHash($entity);
        $fields = array_keys($valueMap);

        $stmnt = $this->pdo->prepare('INSERT INTO '.$tableName.'('.implode(',', $fields).') VALUES(:'.implode(',:', $fields).')');
        $stmnt->execute($valueMap);

        return $this;
    }

    public function query(string $entityClass): Query
    {
        return new Query(
            $this->entityResolver->getTableName($entityClass), 
            $this->entityResolver->getFieldList($entityClass),
            $this->pdo
        ); 
    }

    public function commit(): void
    {
        try {
            $this->pdo->commit();
            $this->queries = [];
            $this->isTransactionStarted = false;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
