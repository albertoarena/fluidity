<?php
use \Fluidity\Fluid\Fluid;
use \Fluidity\Fluid\Member\Property;
use \Fluidity\Fluidizer;


class MockFluid extends Fluid
{
    public function __toString()
    {
        return 'Mock';
    }

    public function toFlat()
    {
        return 'Mock';
    }
}


class FluidTest extends \PHPUnit_Framework_TestCase
{

    /** @var MockFluid */
    protected $mock;

    protected function setUp()
    {
        Fluidizer::clear();
        $this->mock = new MockFluid();
        $this->mock['method1'] = function () {
            return 123;
        };
        $this->mock['method2'] = function () {
            return 'abc';
        };

        $this->mock['event'] = new \Fluidity\Fluid\Member\Event(function () {
            return 'event';
        });
        $this->mock['property1'] = 123;
        $this->mock['property2'] = 'abc';
        $this->mock['property3'] = false;
    }

    /**
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\Fluid::__call
     * @covers \Fluidity\Fluid\Member\Property::__construct
     */
    public function testCallInjected()
    {
        $a = 0;
        $obj = new Property();
        $obj->test = function () use (&$a) {
            ++$a;
        };
        $this->assertEquals(0, $a);
        $obj->test();
        $this->assertEquals(1, $a);
    }

    /**
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\Fluid::__call
     * @covers \Fluidity\Fluid\Member\Property::__construct
     */
    public function testCallInjectedInvalid()
    {
        $obj = new Property();
        $obj->test = 'hello';
        $this->setExpectedException('\Exception', 'Member test is not callable');
        $obj->test();
    }

    /**
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\Fluid::__call
     * @covers \Fluidity\Fluid\Member\Property::__construct
     * @covers \Fluidity\Fluidizer::define
     */
    public function testCallFluidMethod()
    {
        $a = 0;
        Fluidizer::define('test', function () use (&$a) {
            ++$a;
        });
        $this->assertEquals(0, $a);
        $obj = new Property();
        $obj->test();
        $this->assertEquals(1, $a);
    }

    /**
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\Fluid::__call
     * @covers \Fluidity\Fluid\Member\Property::__construct
     * @covers \Fluidity\Fluidizer::define
     */
    public function testCallFluidMethodNotExisting()
    {
        $obj = new Property();
        $this->setExpectedException('\Exception', 'Fluid method hello is not available');
        $obj->hello();
    }

    /**
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\Fluid::__call
     * @covers \Fluidity\Fluid\Fluid::properties
     * @covers \Fluidity\Fluid\Member\Property::__construct
     */
    public function testProperties()
    {
        $properties = $this->mock->properties();
        $this->assertEquals(3, count($properties));
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $properties['property1']);
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $properties['property2']);
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $properties['property3']);
        $this->assertEquals(123, $properties['property1']->toFlat());
        $this->assertEquals('abc', $properties['property2']->toFlat());
        $this->assertFalse($properties['property3']->toFlat());
    }

    /**
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\Fluid::__call
     * @covers \Fluidity\Fluid\Fluid::methods
     * @covers \Fluidity\Fluid\Member\Method::__construct
     */
    public function testMethods()
    {
        $methods = $this->mock->methods();
        $this->assertEquals(2, count($methods));
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Method', $methods['method1']);
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Method', $methods['method2']);
        $this->assertEquals(123, $methods['method1']->call());
        $this->assertEquals('abc', $methods['method2']->call());
    }

    /**
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\Fluid::__call
     * @covers \Fluidity\Fluid\Fluid::events
     * @covers \Fluidity\Fluid\Member\Method::__construct
     */
    public function testEvents()
    {
        $methods = $this->mock->events();
        $this->assertEquals(1, count($methods));
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Event', $methods['event']);
        $this->assertEquals('event', $methods['event']->call());
    }

    /**
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\Fluid::__call
     * @covers \Fluidity\Fluid\Fluid::events
     * @covers \Fluidity\Fluid\Fluid::__clone
     * @covers \Fluidity\Fluid\Member\Method::__construct
     */
    public function testClone()
    {
        $mock2 = clone $this->mock;
        $this->assertEquals(3, count($this->mock->properties()));
        $this->assertEquals(2, count($this->mock->methods()));
        $this->assertEquals(3, count($mock2->properties()));
        $this->assertEquals(2, count($mock2->methods()));
        $mock2['test4'] = 'hello world';
        $mock2['method3'] = function () {
            return null;
        };
        $this->assertEquals(3, count($this->mock->properties()));
        $this->assertEquals(2, count($this->mock->methods()));
        $this->assertEquals(4, count($mock2->properties()));
        $this->assertEquals(3, count($mock2->methods()));
    }

}