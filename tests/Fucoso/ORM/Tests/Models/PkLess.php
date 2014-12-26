<?php

namespace Fucoso\ORM\Tests\Models;

/**
 * A model with no primary key.
 */
class PkLess extends \Fucoso\ORM\Model
{
    protected static $_meta = array(
        'database' => 'testdb',
        'table' => 'pkless',
    );

    public $foo;
    public $bar;
    public $baz;
}
