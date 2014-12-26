<?php

namespace Fucoso\ORM\Tests\Models;

use Fucoso\ORM\Model;

/**
 * Used to test
 */
class Asset extends Model
{
    protected static $_meta = array(
        'database' => 'testdb',
        'table' => 'asset',
        'pk' => 'id'
    );

    public $id;
    public $owner_id;
    public $value;
}
