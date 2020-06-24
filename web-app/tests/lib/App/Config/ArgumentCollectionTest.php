<?php

namespace Tests\Lib\App\Config;

use Lib\App\Config\ArgumentCollection;
use PHPUnit\Framework\TestCase;

class ArgumentCollectionTest extends TestCase
{
    public function testArgument()
    {
        $argCollection = new ArgumentCollection();
        $key = 'test';
        $value = 'SOME';
        $argCollection->setArgument($key, $value);

        $this->assertEquals($value, $argCollection->getArgument($key));
    }
}