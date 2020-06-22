<?php

namespace App\Entity;

use JsonSerializable;
use Lib\Database\EntityInterface;

class User implements EntityInterface, JsonSerializable
{
    /**
     * @var int
     */
    private int $id;

    /**
     * @var string
     */
    private string $username;

    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $currency;

    /**
     * @var float
     */
    private float $total;

    /**
     * @param int $id
     * @param string $username
     * @param string $email
     * @param string $currency
     * @param float $total
     */
    public function __construct(
        int $id,
        string $username,
        string $email,
        string $currency,
        float $total
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->currency = $currency;
        $this->total = $total;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'users';
    }

    /**
     * @return array
     */
    public function getColumnMap(): array
    {
        return [
            'id'       => 'id',
            'username' => 'username',
            'email'    => 'email',
            'currency' => 'currency',
            'total'    => 'total',
        ];
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    public function jsonSerialize()
    {
        $result = [];
        foreach ($this->getColumnMap() as $val) {
            $result[$val] = $this->{$val};
        }

        return $result;
    }
}
