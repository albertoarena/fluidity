<?php
use Fluidity\Fluid\Iterator;


class MockFluidIterator extends Iterator
{
    public function __construct($properties)
    {
        parent::__construct();
        $members = & $this->fluids->get(self::MEMBERS);
        foreach ($properties as $key => $value) {
            $members[$key] = \Fluidity\Fluid\MemberFactory::createMember($value);
        }
    }
}

class FluidIteratorTest extends \PHPUnit_Framework_TestCase
{
    /** @var MockFluidIterator */
    protected $mock;

    /** @var array */
    protected $properties;

    protected function setUp()
    {
        $this->properties = array('a' => 1, 'b' => 2, 'c' => 3);
        $this->mock = new MockFluidIterator($this->properties);
    }

    /**
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Iterator::rewind
     * @covers Fluidity\Fluid\Iterator::current
     * @covers Fluidity\Fluid\Iterator::key
     * @covers Fluidity\Fluid\Iterator::next
     * @covers Fluidity\Fluid\Iterator::valid
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Member::toFlat
     * @covers Fluidity\Fluid\Member\Member::get
     */
    public function testMethods()
    {
        $this->mock->rewind();
        $this->assertTrue($this->mock->valid());
        $this->assertEquals('a', $this->mock->key());
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->mock->current());
        $this->assertEquals(1, $this->mock->current()->toFlat());
        $this->mock->next();
        $this->assertTrue($this->mock->valid());
        $this->assertEquals('b', $this->mock->key());
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->mock->current());
        $this->assertEquals(2, $this->mock->current()->toFlat());
        $this->mock->next();
        $this->assertTrue($this->mock->valid());
        $this->assertEquals('c', $this->mock->key());
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->mock->current());
        $this->assertEquals(3, $this->mock->current()->toFlat());
        $this->mock->next();
        $this->assertFalse($this->mock->valid());
    }

    /**
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Iterator::rewind
     * @covers Fluidity\Fluid\Iterator::current
     * @covers Fluidity\Fluid\Iterator::key
     * @covers Fluidity\Fluid\Iterator::next
     * @covers Fluidity\Fluid\Iterator::valid
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Member::toFlat
     * @covers Fluidity\Fluid\Member\Member::get
     */
    public function testLoop()
    {
        $i = 0;
        foreach ($this->mock as $key => $value) {
            $key1 = array_keys($this->properties)[$i];
            $this->assertTrue($this->mock->valid());
            $this->assertEquals($key1, $key);
            $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $value);
            $this->assertEquals($this->properties[$key1], $value->toFlat());
            $i++;
        }
    }

    /**
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Iterator::fluids
     **/
    public function testFluids()
    {
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Storage', $this->mock->fluids());
    }

} 