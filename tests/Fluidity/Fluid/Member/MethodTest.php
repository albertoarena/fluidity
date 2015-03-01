<?php
use \Fluidity\Fluid\Member\Method;


class MethodTest extends \PHPUnit_Framework_TestCase
{
    /** @var callable */
    protected $callback;

    /** @var \Fluidity\Fluid\Member\Method */
    protected $method;

    public function setUp()
    {
        $this->callback = function () {
            echo 'hello';
        };
        $this->method = new Method($this->callback);
    }

    /**
     * @covers Fluidity\Fluid\Member\Method::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Member\Member::getId
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Method::set
     * @covers Fluidity\Fluid\Member\Method::get
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Method', $this->method);
        $this->assertInstanceOf('Closure', $this->method->get());
        $this->assertEquals('method', $this->method->getId());
    }

    /**
     * @covers Fluidity\Fluid\Member\Method::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Method::set
     * @covers Fluidity\Fluid\Member\Method::get
     */
    public function testSetInvalid()
    {
        $this->setExpectedException('\Exception', 'Invalid value for a Fluid method');
        $this->method->set(123);
    }

    /**
     * @covers Fluidity\Fluid\Member\Method::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Method::set
     * @covers Fluidity\Fluid\Member\Method::__toString
     */
    public function testToString()
    {
        $this->expectOutputString('[method]');
        echo $this->method;
    }

    /**
     * @covers Fluidity\Fluid\Member\Method::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Method::set
     * @covers Fluidity\Fluid\Member\Method::isCallable
     */
    public function testIsCallable()
    {
        $this->assertTrue($this->method->isCallable());
    }

    /**
     * @covers Fluidity\Fluid\Member\Method::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Method::set
     * @covers Fluidity\Fluid\Member\Method::get
     * @covers Fluidity\Fluid\Member\Method::call
     * @covers Fluidity\Fluid\Member\Method::__clone
     */
    public function testClone()
    {
        $callback2 = function () {
            echo ' world';
        };

        $method2 = clone $this->method;
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Method', $method2);
        $this->method->set($callback2);
        $this->expectOutputString('hello world');
        $method2->call();
        $this->method->call();
    }

    /**
     * @covers Fluidity\Fluid\Member\Method::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Method::set
     * @covers Fluidity\Fluid\Member\Method::get
     * @covers Fluidity\Fluid\Member\Method::call
     */
    public function testCall()
    {
        $this->expectOutputString('hello');
        $this->method->call();
    }

} 