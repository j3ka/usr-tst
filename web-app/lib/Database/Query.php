<?php

namespace Lib\Database;

class Query
{
    /**
     * @var string
     */
    private string $table;

    /**
     * @var array
     */
    private array $fields;

    /**
     * @var \PDO
     */
    private \PDO $pdo;

    /**
     * @var array
     */
    private array $filters;

    /**
     * @var array
     */
    private array $search;

    /**
     * @var int
     */
    private int $limit;

    /**
     * @param string $table
     * @param array $fields
     */
    public function __construct(string $table, array $fields, \PDO $pdo)
    {
        $this->table = $table;
        $this->fields = $fields;
        $this->pdo = $pdo;
        $this->filters = [];
        $this->search = [];
        $this->limit = 0;
    }

    /**
     * @param array $criteria
     *
     * @return self
     */
    public function filterBy(array $criteria): self
    {
        foreach ($criteria as $fieldName => $fieldValue) {
            if (!in_array($fieldName, $this->fields)) {
                continue;
            }
            $this->filters[$fieldName] = $fieldValue;
        }

        return $this;
    }

    public function searchBy(string $fieldName, $fieldValue): self
    {
        if (!in_array($fieldName, $this->fields)) {
            throw new \InvalidArgumentException('Field "'.$fieldName.'" not existst in table '.$this->table);
        }

        $this->search = [$fieldName => $fieldValue.'%'];

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = sqrt($limit * $limit);

        return $this;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        $query = $this->getRawQuery();

        return $this->executeQuery($query);
    }

    /**
     * @return string
     */
    private function getRawQuery(): string
    {
        $query = 'SELECT * FROM '.$this->table;

        if (!empty($this->filters) || !empty($this->search)) {
            $query .= ' WHERE ';

            foreach ($this->search as $fieldName => $fieldValue) {
                $query .= $fieldName.' LIKE :search_'.$fieldName.' AND ';
            }

            foreach (array_keys($this->filters) as $fieldName) {
                $query .= $fieldName.' = :'.$fieldName.' AND ';
            }
            $query = trim($query, ' AND');
        }

        if ($this->limit > 0) {
            $query .= ' LIMIT '.(string)$this->limit;
        }
        
        return $query;
    }

    /**
     * @param string $query
     *
     * @return array
     */
    private function executeQuery(string $query): array
    {
        try {
            $stmnt = $this->pdo->prepare($query);
            $params = $this->filters;
            if (!empty($this->search)) {
                foreach ($this->search as $key => $value) {
                    $params['search_'.$key] = $value;
                }
            }
            $stmnt->execute($params);
            $result = $stmnt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw $e;
        }

        return $result;
    }
}
