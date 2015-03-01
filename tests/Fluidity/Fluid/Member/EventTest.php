<?php
use \Fluidity\Fluid\Member\Event;


class EventTest extends \PHPUnit_Framework_TestCase
{
    /** @var callable */
    protected $callback;

    /** @var \Fluidity\Fluid\Member\Event */
    protected $event;

    public function setUp()
    {
        $this->callback = function () {
            echo 'hello';
        };
        $this->event = new Event($this->callback);
    }

    /**
     * @covers Fluidity\Fluid\Member\Event::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Member\Member::getId
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Event::set
     * @covers Fluidity\Fluid\Member\Event::get
     */
    public function testConstruct()
    {
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Event', $this->event);
        $this->assertInstanceOf('Closure', $this->event->get());
        $this->assertEquals('event', $this->event->getId());
    }

    /**
     * @covers Fluidity\Fluid\Member\Event::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Event::set
     * @covers Fluidity\Fluid\Member\Event::get
     */
    public function testSetInvalid()
    {
        $this->setExpectedException('\Exception', 'Invalid value for a Fluid event');
        $this->event->set(123);
    }

    /**
     * @covers Fluidity\Fluid\Member\Event::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Event::set
     * @covers Fluidity\Fluid\Member\Event::__toString
     */
    public function testToString()
    {
        $this->expectOutputString('[event]');
        echo $this->event;
    }

    /**
     * @covers Fluidity\Fluid\Member\Event::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Event::set
     * @covers Fluidity\Fluid\Member\Event::isCallable
     */
    public function testIsCallable()
    {
        $this->assertTrue($this->event->isCallable());
    }

    /**
     * @covers Fluidity\Fluid\Member\Event::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Event::set
     * @covers Fluidity\Fluid\Member\Event::get
     * @covers Fluidity\Fluid\Member\Event::call
     * @covers Fluidity\Fluid\Member\Event::__clone
     */
    public function testClone()
    {
        $callback2 = function () {
            echo ' world';
        };

        $event2 = clone $this->event;
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Event', $event2);
        $this->event->set($callback2);
        $this->expectOutputString('hello world');
        $event2->call();
        $this->event->call();
    }

    /**
     * @covers Fluidity\Fluid\Member\Event::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Event::set
     * @covers Fluidity\Fluid\Member\Event::get
     * @covers Fluidity\Fluid\Member\Event::call
     */
    public function testCall()
    {
        $this->expectOutputString('hello');
        $this->event->call();
    }

} 