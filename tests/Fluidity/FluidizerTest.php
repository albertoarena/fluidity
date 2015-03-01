<?php
use \Fluidity\Fluidizer;


class FluidizerTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        Fluidizer::clear();
    }

    /**
     * @covers Fluidity\Fluidizer::define
     * @covers Fluidity\Fluidizer::get
     */
    public function testDefine()
    {
        $this->assertNull(Fluidizer::get('test'));
        Fluidizer::define('test', function () {
        });
        $test = Fluidizer::get('test');
        $this->assertNotNull($test);
        $this->assertEquals('Closure', get_class($test));
    }

    /**
     * @covers Fluidity\Fluidizer::define
     * @covers Fluidity\Fluidizer::get
     */
    public function testDefineInvalid()
    {
        $this->assertNull(Fluidizer::get('test'));
        $this->setExpectedException('\Exception', 'Only a callable or closure can be associated to a fluid method');
        Fluidizer::define('test', 'hello');
    }

    /**
     * @covers Fluidity\Fluidizer::define
     * @covers Fluidity\Fluidizer::get
     */
    public function testCallDefined()
    {
        $a = 0;
        $callback = function ($value) use (&$a) {
            $a = $value;
        };

        $this->assertNull(Fluidizer::get('test'));
        Fluidizer::define('test', $callback);
        $test = Fluidizer::get('test');
        $this->assertNotNull($test);
        $this->assertEquals('Closure', get_class($test));
        $this->assertEquals(0, $a);
        call_user_func($test, 1);
        $this->assertEquals(1, $a);
    }

    /**
     * @covers Fluidity\Fluidizer::define
     * @covers Fluidity\Fluidizer::undefine
     * @covers Fluidity\Fluidizer::get
     */
    public function testUnDefine()
    {
        $this->assertNull(Fluidizer::get('test'));
        Fluidizer::define('test', function () {
        });
        $test = Fluidizer::get('test');
        $this->assertNotNull($test);
        Fluidizer::undefine('test');
        $this->assertNull(Fluidizer::get('test'));
    }

    /**
     * @covers Fluidity\Fluidizer::define
     * @covers Fluidity\Fluidizer::clear
     * @covers Fluidity\Fluidizer::get
     */
    public function testClear()
    {
        $this->assertNull(Fluidizer::get('test1'));
        $this->assertNull(Fluidizer::get('test2'));
        Fluidizer::define('test1', function () {
        });
        Fluidizer::define('test2', function () {
        });
        $this->assertNotNull(Fluidizer::get('test1'));
        $this->assertNotNull(Fluidizer::get('test2'));
        Fluidizer::clear();
        $this->assertNull(Fluidizer::get('test1'));
        $this->assertNull(Fluidizer::get('test2'));
    }

    /**
     * @covers Fluidity\Fluidizer::define
     * @covers Fluidity\Fluidizer::clear
     * @covers Fluidity\Fluidizer::getList
     */
    public function testGetList()
    {
        $this->assertNull(Fluidizer::get('test1'));
        $this->assertNull(Fluidizer::get('test2'));
        Fluidizer::define('test1', function () {
        });
        Fluidizer::define('test2', function () {
        });
        $list = Fluidizer::getList();
        $this->assertTrue(is_array($list));
        $this->assertEquals(2, count($list));
        $this->assertEquals('test1', $list[0]);
        $this->assertEquals('test2', $list[1]);
    }
} 