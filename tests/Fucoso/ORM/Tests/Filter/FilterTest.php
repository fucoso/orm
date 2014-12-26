<?php

namespace Fucoso\ORM\Tests;

use Fucoso\ORM\Tests\Models\Person;

use Fucoso\ORM\DB;

/**
 * Tests in this class actually run some queries, unlike other test classes in
 * this namespace.
 *
 * @group filter
 */
class FilterTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        DB::configure(ORM_CONFIG_FILE);
    }

    public function testCaseInsensitiveLike()
    {
        $qs = Person::objects()->filter('name', 'ilike', 'pero');

        $qs->delete();
        $this->assertFalse($qs->exists());

        Person::fromArray(array('name' => "PERO"))->insert();
        Person::fromArray(array('name' => "pero"))->insert();
        Person::fromArray(array('name' => "Pero"))->insert();
        Person::fromArray(array('name' => "pERO"))->insert();

        $this->assertSame(4, $qs->count());
        $this->assertCount(4, $qs->fetch());
    }
}