<?php

namespace Fucoso\ORM\Tests\Models;

class Person extends \Fucoso\ORM\Model
{
    protected static $_meta = array(
        'database' => 'testdb',
        'table' => 'person',
        'pk' => 'id'
    );

    public $id;
    public $name;
    public $email;
    public $birthday;
    public $created;
    public $income;
}
