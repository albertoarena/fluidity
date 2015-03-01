<?php
use Fluidity\Fluid\ArrayAccess;

class MockFluidArray extends ArrayAccess
{
}

class MockWithPropertyFluidArray extends MockFluidArray
{
    protected $something;

    public function __construct()
    {
        parent::__construct();
        $this->something = 'John';
    }

    public function getSomething()
    {
        return $this->something;
    }

    public function setSomething($v)
    {
        $this->something = $v;
    }

    public function helloSomething()
    {
        return 'Hello ' . $this->something;
    }
}

class ArrayAccessTest extends \PHPUnit_Framework_TestCase
{

    /** @var MockFluidArray */
    protected $mock;

    public function setUp()
    {
        $this->mock = new MockFluidArray();
    }

    /**
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers Fluidity\Fluid\ArrayAccess::offsetExists
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::toFlat
     */
    public function testGetSetProperty()
    {
        $this->assertNull($this->mock->offsetGet('test'));
        $this->mock->offsetSet('test', 123);
        $this->assertEquals(123, $this->mock->offsetGet('test')->toFlat());
        $this->assertTrue($this->mock->offsetExists('test'));
        $this->mock->offsetSet('test', 456);
        $this->assertEquals(456, $this->mock->offsetGet('test')->toFlat());
    }

    /**
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Method::__construct
     * @covers Fluidity\Fluid\Member\Method::set
     * @covers Fluidity\Fluid\Member\Method::toFlat
     */
    public function testGetSetMethod()
    {
        $a = 0;
        $callback = function ($n) use (&$a) {
            $a += $n;
        };

        $this->assertNull($this->mock->offsetGet('test'));
        $this->mock->offsetSet('test', $callback);
        $method = $this->mock->offsetGet('test');
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Method', $method);
        $this->assertInstanceOf('Closure', $method->toFlat());
        $this->assertEquals(0, $a);
        call_user_func($method->toFlat(), 1);
        $this->assertEquals(1, $a);
        $method->call(2);
        $this->assertEquals(3, $a);
    }

    /**
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers Fluidity\Fluid\ArrayAccess::offsetExists
     * @covers Fluidity\Fluid\MemberFactory::createMember
     */
    public function testExistsProperty()
    {
        $this->assertFalse($this->mock->offsetExists('test'));
        $this->mock->offsetSet('test', 123);
        $this->assertTrue($this->mock->offsetExists('test'));
    }

    /**
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers Fluidity\Fluid\ArrayAccess::__set
     * @covers Fluidity\Fluid\ArrayAccess::__get
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::toFlat
     */
    public function testSetExistingProperty()
    {
        $this->mock = new MockWithPropertyFluidArray();
        $this->assertEquals('John', $this->mock->getSomething());
        $this->assertNull($this->mock->something);
        $this->assertNull($this->mock['something']);
        $this->assertEquals('Hello John', $this->mock->helloSomething());
        $this->mock->something = 'Jim';
        $this->assertEquals('Jim', $this->mock->getSomething());
        $this->assertEquals('Jim', $this->mock->something);
        $this->assertEquals('Jim', $this->mock['something']);
        $this->assertEquals('Hello Jim', $this->mock->helloSomething());
        $this->mock->something = 'Ross';
        $this->assertEquals('Ross', $this->mock->getSomething());
        $this->assertEquals('Ross', $this->mock->something);
        $this->assertEquals('Ross', $this->mock['something']);
        $this->assertEquals('Hello Ross', $this->mock->helloSomething());
    }

    /**
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers Fluidity\Fluid\ArrayAccess::offsetExists
     * @covers Fluidity\Fluid\ArrayAccess::offsetUnset
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::toFlat
     */
    public function testOffsetUnsetProperty()
    {
        $this->assertFalse($this->mock->offsetExists('test'));
        $this->mock->offsetSet('test', 123);
        $this->assertTrue($this->mock->offsetExists('test'));
        $this->mock->offsetUnset('test');
        $this->assertFalse($this->mock->offsetExists('test'));
    }

    /**
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers Fluidity\Fluid\ArrayAccess::offsetExists
     * @covers Fluidity\Fluid\ArrayAccess::offsetUnset
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Method::__construct
     * @covers Fluidity\Fluid\Member\Method::set
     * @covers Fluidity\Fluid\Member\Method::toFlat
     */
    public function testOffsetUnsetMethod()
    {
        $a = 0;
        $callback = function () use (&$a) {
            $a = 1;
        };

        $this->assertNull($this->mock->offsetGet('test'));
        $this->mock->offsetSet('test', $callback);
        $method = $this->mock->offsetGet('test');
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Method', $method);
        $this->mock->offsetUnset('test');
        $this->assertFalse($this->mock->offsetExists('test'));
    }

    /**
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers Fluidity\Fluid\ArrayAccess::offsetUnset
     * @covers Fluidity\Fluid\ArrayAccess::__set
     * @covers Fluidity\Fluid\ArrayAccess::__get
     * @covers Fluidity\Fluid\ArrayAccess::__unset
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::toFlat
     */
    public function testUnsetExistingProperty()
    {
        $this->mock = new MockWithPropertyFluidArray();
        $this->assertEquals('John', $this->mock->getSomething());
        $this->assertNull($this->mock->something);
        $this->assertNull($this->mock['something']);
        $this->assertEquals('Hello John', $this->mock->helloSomething());
        $this->mock->something = 'Jim';
        $this->assertEquals('Jim', $this->mock->getSomething());
        $this->assertEquals('Jim', $this->mock->something);
        $this->assertEquals('Jim', $this->mock['something']);
        $this->assertEquals('Hello Jim', $this->mock->helloSomething());
        unset($this->mock->something);
        $this->assertNull($this->mock->getSomething());
        $this->assertNull($this->mock->something);
        $this->assertNull($this->mock['something']);
        $this->assertEquals('Hello ', $this->mock->helloSomething());
    }

    /**
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers Fluidity\Fluid\ArrayAccess::offsetExists
     * @covers Fluidity\Fluid\ArrayAccess::__set
     * @covers Fluidity\Fluid\ArrayAccess::__get
     * @covers Fluidity\Fluid\ArrayAccess::__isset
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::toFlat
     */
    public function testIssetProperty()
    {
        $this->mock = new MockWithPropertyFluidArray();
        $this->assertFalse(isset($this->mock->something));
        $this->assertFalse(isset($this->mock['something']));
        $this->mock->something = 'Jim';
        $this->assertTrue(isset($this->mock->something));
        $this->assertTrue(isset($this->mock['something']));
    }

    /**
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers Fluidity\Fluid\ArrayAccess::offsetUnset
     * @covers Fluidity\Fluid\ArrayAccess::offsetExists
     * @covers Fluidity\Fluid\ArrayAccess::__get
     * @covers Fluidity\Fluid\ArrayAccess::__set
     * @covers Fluidity\Fluid\ArrayAccess::__unset
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::toFlat
     */
    public function testArrayAccess()
    {
        $this->assertNull($this->mock['var']);
        $this->mock['var'] = 123;
        $this->assertEquals(123, $this->mock['var']->toFlat());
        $this->assertTrue(isset($this->mock['var']));
        unset($this->mock['var']);
        $this->assertNull($this->mock['var']);
    }

} 