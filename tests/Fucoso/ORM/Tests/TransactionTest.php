<?php

namespace Fucoso\ORM\Tests;

use Mockery as m;

use Fucoso\ORM\Connection;
use Fucoso\ORM\DB;

use Fucoso\ORM\Tests\Models\Person;

/**
 * @group transaction
 */
class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        DB::configure(ORM_CONFIG_FILE);
    }

    public function testManualBeginCommit()
    {
        $person = new Person();
        $person->name = 'Bruce Dickinson';
        $person->income = 12345;
        $person->save();

        $id = $person->id;

        DB::begin();

        $p = Person::get($id);
        $p->income = 54321;
        $p->save();

        DB::commit();

        $this->assertEquals(54321, Person::get($id)->income);
    }

    public function testManualBeginRollback()
    {
        $person = new Person();
        $person->name = 'Steve Harris';
        $person->income = 12345;
        $person->save();

        $id = $person->id;

        DB::begin();

        $p = Person::get($id);
        $p->income = 54321;
        $p->save();

        DB::rollback();

        $this->assertEquals(12345, Person::get($id)->income);
    }

    public function testCallbackTransactionCommit()
    {
        $person = new Person();
        $person->name = 'Dave Murray';
        $person->income = 12345;
        $person->save();

        $id = $person->id;

        DB::transaction(function() use ($id) {
            $p = Person::get($id);
            $p->income = 54321;
            $p->save();
        });

        $this->assertEquals(54321, Person::get($id)->income);
    }

    public function testCallbackTransactionRollback()
    {
        $person = new Person();
        $person->name = 'Adrian Smith';
        $person->income = 12345;
        $person->save();

        $id = $person->id;

        try {
            DB::transaction(function() use ($id) {
                $p = Person::get($id);
                $p->income = 54321;
                $p->save();

                throw new \Exception("Aborting");
            });

            self::fail("This code should not be reachable.");

        } catch (\Exception $ex) {
            // Expected. Do nothing.
        }

        // Check changes have been rolled back
        $this->assertEquals(12345, Person::get($id)->income);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Given argument is not callable.
     */
    public function testCallbackInvalidArgument()
    {
        DB::transaction(10);
    }

    public function testDisconnectRollsBackTransaction()
    {
        $person = new Person();
        $person->name = 'Nicko McBrain';
        $person->income = 12345;
        $person->save();

        $id = $person->id;

        DB::begin();

        $p = Person::get($id);
        $p->income = 54321;
        $p->save();

        // This should roll back changes
        DB::disconnect('testdb');

        // So they won't be commited here
        DB::commit();

        $this->assertEquals(12345, Person::get($id)->income);
    }

    public function testDisconnectAllRollsBackTransaction()
    {
        $person = new Person();
        $person->name = 'Nicko McBrain';
        $person->income = 12345;
        $person->save();

        $id = $person->id;

        DB::begin();

        $p = Person::get($id);
        $p->income = 54321;
        $p->save();

        DB::disconnectAll();

        $this->assertEquals(12345, Person::get($id)->income);
    }

    public function testExecuteTransaction()
    {
        $person = new Person();
        $person->name = 'Janick Gers';
        $person->income = 100;
        $person->insert();

        $id = $person->id;
        $conn = DB::getConnection('testdb');

        DB::begin();
        $conn->execute("UPDATE person SET income = income + 1");
        DB::rollback();

        $this->assertEquals(100, Person::get($id)->income);

        DB::begin();
        $conn->execute("UPDATE person SET income = income + 1");
        DB::commit();

        $this->assertEquals(101, Person::get($id)->income);
    }

    public function testPreparedExecuteTransaction()
    {
        $person = new Person();
        $person->name = 'Janick Gers';
        $person->income = 100;
        $person->insert();

        $id = $person->id;
        $conn = DB::getConnection('testdb');

        DB::begin();
        $conn->preparedExecute("UPDATE person SET income = ?", array(200));
        DB::rollback();

        $this->assertEquals(100, Person::get($id)->income);

        DB::begin();
        $conn->preparedExecute("UPDATE person SET income = ?", array(200));
        DB::commit();

        $this->assertEquals(200, Person::get($id)->income);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Cannot roll back. Not in transaction.
     */
    public function testRollbackBeforeBegin()
    {
        DB::rollback();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Cannot commit. Not in transaction.
     */
    public function testCommitBeforeBegin()
    {
        DB::commit();
    }

    public function testDoubleBegin()
    {
        DB::begin();

        try {
            DB::begin();
            $this->fail('Expected an exception here.');
        } catch (\Exception $e) {
            $this->assertContains("Already in transaction.", $e->getMessage());
        }

        DB::rollback();
    }
}
