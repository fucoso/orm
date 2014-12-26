<?php

namespace Fucoso\ORM\Tests;

use Fucoso\ORM\DB;
use Fucoso\ORM\Meta;
use Fucoso\ORM\QuerySet;
use Fucoso\ORM\Tests\Models\Person;
use Fucoso\ORM\Tests\Models\Contact;
use Fucoso\ORM\Tests\Models\Asset;

/**
 * @group model
 */
class ModelRelationsTraitTest extends \PHPUnit_Framework_TestCase
{
    private static $person;

    public static function setUpBeforeClass()
    {
        DB::configure(ORM_CONFIG_FILE);

        self::$person = Person::fromArray(['name' => 'Udo Dirkschneider']);
        self::$person->save();
    }

    public function testGuessableRelation()
    {
        $pid = self::$person->id;

        // Contacts are linked to person via a guessable foreign key name
        // (person_id)
        $c1 = Contact::fromArray(['person_id' => $pid, "value" => "Contact #1"]);
        $c2 = Contact::fromArray(['person_id' => $pid, "value" => "Contact #2"]);
        $c3 = Contact::fromArray(['person_id' => $pid, "value" => "Contact #3"]);

        $c1->save();
        $c2->save();
        $c3->save();

        $contacts = self::$person->hasChildren("Fucoso\ORM\Tests\\Models\\Contact");
        $this->assertInstanceOf("Fucoso\ORM\QuerySet", $contacts);

        $actual = $contacts->fetch();
        $expected = [$c1, $c2, $c3];
        $this->assertEquals($expected, $actual);

        $p1 = $c1->hasParent("Fucoso\ORM\Tests\\Models\\Person")->single();
        $p2 = $c2->hasParent("Fucoso\ORM\Tests\\Models\\Person")->single();
        $p3 = $c3->hasParent("Fucoso\ORM\Tests\\Models\\Person")->single();

        $this->assertEquals(self::$person, $p1);
        $this->assertEquals(self::$person, $p2);
        $this->assertEquals(self::$person, $p3);
    }

    public function testUnguessableRelation()
    {
        $pid = self::$person->id;

        // Asset is similar to contact, but has a non-guessable foreign key name
        // (owner_id)
        $a1 = Asset::fromArray(['owner_id' => $pid, "value" => "Asset #1"]);
        $a2 = Asset::fromArray(['owner_id' => $pid, "value" => "Asset #2"]);
        $a3 = Asset::fromArray(['owner_id' => $pid, "value" => "Asset #3"]);

        $a1->save();
        $a2->save();
        $a3->save();

        $assets = self::$person->hasChildren("Fucoso\ORM\Tests\\Models\\Asset", "owner_id");
        $this->assertInstanceOf("Fucoso\ORM\QuerySet", $assets);

        $actual = $assets->fetch();
        $expected = [$a1, $a2, $a3];
        $this->assertEquals($expected, $actual);

        $p1 = $a1->hasParent("Fucoso\ORM\Tests\\Models\\Person", "owner_id")->single();
        $p2 = $a2->hasParent("Fucoso\ORM\Tests\\Models\\Person", "owner_id")->single();
        $p3 = $a3->hasParent("Fucoso\ORM\Tests\\Models\\Person", "owner_id")->single();

        $this->assertEquals(self::$person, $p1);
        $this->assertEquals(self::$person, $p2);
        $this->assertEquals(self::$person, $p3);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Model class "foo" does not exist
     */
    public function testInvalidModel1()
    {
        // Class does not exist
        self::$person->hasChildren("foo");
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Given class "DateTime" is not a subclass of Fucoso\ORM\Model
     */
    public function testInvalidModel2()
    {
        // Class exists but is not a model
        self::$person->hasChildren("DateTime");
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Empty key given
     */
    public function testInvalidKey1()
    {
        // Empty key
        self::$person->hasChildren("Fucoso\ORM\Tests\\Models\\Contact", []);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Invalid key type: "object". Expected string or array.
     */
    public function testInvalidKey2()
    {
        // Key is a class instead of string or array
        self::$person->hasChildren("Fucoso\ORM\Tests\\Models\\Contact", new Contact());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Property "foo" does not exist
     */
    public function testInvalidKey3()
    {
        // Property does not exist
        self::$person->hasChildren("Fucoso\ORM\Tests\\Models\\Contact", "foo");
    }
}
