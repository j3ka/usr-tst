<?php

namespace Tests\App\Factory;

use App\Entity\User;
use App\Factory\UserFactory;
use Lib\Database\EntityFieldsResolver;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    public function testCreate()
    {
        $id = 1;
        $name = 'test';
        $email = 'test@example.com';
        $currency = 'USD';
        $total = 100.0;

        $user = $this->getFactory()->create(
            $id,
            $name,
            $email,
            $currency,
            $total
        );

        $this->assertTrue($user instanceof User);

        $this->assertTrue($user->getId()       === $id);
        $this->assertTrue($user->getUsername() === $name);
        $this->assertTrue($user->getEmail()    === $email);
        $this->assertTrue($user->getCurrency() === $currency);
        $this->assertTrue($user->getTotal()    === $total);
    }

    public function testArrayToEntityEmptyData()
    {
        $input = [];
        $this->expectException(\InvalidArgumentException::class);
        $this->getFactory()->arrayToEntity($input);
    }

    public function testArrayToEntity()
    {
        $input = [
            'id'       => 1,
            'username'     => 'test',
            'email'    => 'test@example.com',
            'currency' => 'USD',
            'total'    => 100.0,
        ];

        $user = $this->getFactory()->arrayToEntity($input);

        $this->assertTrue($user instanceof User);
        $this->assertTrue($user->getId()       === $input['id']);
        $this->assertTrue($user->getUsername() === $input['username']);
        $this->assertTrue($user->getEmail()    === $input['email']);
        $this->assertTrue($user->getCurrency() === $input['currency']);
        $this->assertTrue($user->getTotal()    === $input['total']);
    }

    private function getFactory(): UserFactory
    {
        $fieldsResolver = new EntityFieldsResolver();

        return new UserFactory($fieldsResolver);
    }
}