<?php

namespace Fucoso\ORM\Tests;

use Fucoso\ORM\Tests\Models\Person;

use Fucoso\ORM\Filter\ColumnFilter;
use Fucoso\ORM\Filter\CompositeFilter;
use Fucoso\ORM\Filter\Filter;

/**
 * @group filter
 */
class CompositeFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryAndOr()
    {
        $actual = Filter::_and();
        $expected = new CompositeFilter(CompositeFilter::OP_AND);
        $this->assertEquals($expected, $actual);

        $actual = Filter::_or();
        $expected = new CompositeFilter(CompositeFilter::OP_OR);
        $this->assertEquals($expected, $actual);
    }

    public function testCompositeFilter1()
    {
        $filter = new CompositeFilter(
            CompositeFilter::OP_OR,
            array(
                ColumnFilter::fromArray(array('id', '=', 1)),
                ColumnFilter::fromArray(array('id', '=', 2)),
                ColumnFilter::fromArray(array('id', '=', 3)),
            )
        );

        $actual = $filter->render();
        $expected = array("(id = ? OR id = ? OR id = ?)", array(1, 2, 3));
        $this->assertSame($expected, $actual);
    }

    public function testCompositeFilter2()
    {
        $filter = new CompositeFilter(
            CompositeFilter::OP_OR,
            array(
                array('id', '=', 1),
                array('id', '=', 2),
                array('id', '=', 3),
            )
        );

        $actual = $filter->render();
        $expected = array("(id = ? OR id = ? OR id = ?)", array(1, 2, 3));
        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Invalid composite filter operation [foo]. Expected one of: AND, OR
     */
    public function testInvalidOperation()
    {
        $filter = new CompositeFilter('foo');
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testAddInvalid()
    {
        $filter = new CompositeFilter("AND");
        $filter->add(1);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Canot render composite filter. No filters defined.
     */
    public function testRenderEmpty()
    {
        $filter = new CompositeFilter("AND");
        $filter->render();
    }
}
