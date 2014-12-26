<?php

namespace Fucoso\ORM\Tests;

use Fucoso\ORM\DB;
use Fucoso\ORM\Meta;
use Fucoso\ORM\Parser;

use Fucoso\ORM\Tests\Models\Person;
use Fucoso\ORM\Tests\Models\Trade;
use Fucoso\ORM\Tests\Models\PkLess;

/**
 * @group meta
 */
class MetaTest extends \PHPUnit_Framework_TestCase
{
    private $testMeta = array(
        'table' => 'person',
        'database' => 'testdb',
        'pk' => 'id'
    );

    public function testPersonMeta()
    {
        $expected = new Meta();
        $expected->table = 'person';
        $expected->class = 'Fucoso\ORM\Tests\\Models\\Person';
        $expected->database = 'testdb';
        $expected->columns = array('id', 'name', 'email', 'birthday', 'created', 'income');
        $expected->pk = array('id');
        $expected->nonPK = array('name', 'email', 'birthday', 'created', 'income');

        $actual = Person::getMeta();
        $this->assertEquals($expected, $actual);
    }

    public function testTradeMeta()
    {
        $expected = new Meta();
        $expected->table = 'trade';
        $expected->class = 'Fucoso\ORM\Tests\\Models\\Trade';
        $expected->database = 'testdb';
        $expected->columns = array('tradedate', 'tradeno', 'price', 'quantity');
        $expected->pk = array('tradedate', 'tradeno');
        $expected->nonPK = array('price', 'quantity');

        $actual = Trade::getMeta();
        $this->assertEquals($expected, $actual);
    }

    public function testPkLessMeta()
    {
        $expected = new Meta();
        $expected->table = 'pkless';
        $expected->class = 'Fucoso\ORM\Tests\\Models\\PkLess';
        $expected->database = 'testdb';
        $expected->columns = array('foo', 'bar', 'baz');
        $expected->pk = null;
        $expected->nonPK = array('foo', 'bar', 'baz');

        $actual = PkLess::getMeta();
        $this->assertEquals($expected, $actual);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Not an array.
     */
    public function testParserInvalidMeta()
    {
        Parser::getMeta('xxx', 'xxx');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Missing 'database'
     */
    public function testParserNoDatabase()
    {
        $meta = $this->testMeta;
        unset($meta['database']);
        Parser::getMeta('xxx', $meta);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Missing 'table'
     */
    public function testParserNoTable()
    {
        $meta = $this->testMeta;
        unset($meta['table']);
        Parser::getMeta('xxx', $meta);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid \Fucoso\ORM\Tests\Models\Person::$_meta['pk']. Not a string or array.
     */
    public function testParserInvalidPK()
    {
        $meta = $this->testMeta;
        $meta['pk'] = 1;
        Parser::getMeta('\Fucoso\ORM\Tests\Models\Person', $meta);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Given primary key column [xxx] does not exist.
     */
    public function testParserNonexistantPK()
    {
        $meta = $this->testMeta;
        $meta['pk'] = 'xxx';
        Parser::getMeta('\Fucoso\ORM\Tests\Models\Person', $meta);
    }

    public function testGetMeta()
    {
        // Just to improve code coverage
        $meta1 = Person::getMeta();
        $meta2 = Person::objects()->getMeta();

        $this->assertSame($meta1, $meta2);
    }
}
