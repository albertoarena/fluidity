<?php
use \Fluidity\Fluid\Member\Property;


class MockPropertyTest extends Property
{
    public $test;

    public function __construct($test)
    {
        parent::__construct();
        $this->test = $test;
    }

    public function test1()
    {
        // Original properties
        return $this->test;
    }

    public function test2()
    {
        // Magic properties
        return $this['test'];
    }
}

class PropertyTest extends \PHPUnit_Framework_TestCase
{
    /** @var callable */
    protected $callback;

    /** @var \Fluidity\Fluid\Member\Property */
    protected $property;

    public function setUp()
    {
        $this->property = new Property('abc');
    }

    /**
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Member\Member::getId
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::get
     */
    public function testConstructScalar()
    {
        $this->property = new Property('abc');
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->property);
        $this->assertEquals('property', $this->property->getId());
        $this->assertEquals('string', gettype($this->property->get()));
        $this->assertEquals('abc', $this->property->get());

        $this->property = new Property(123);
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->property);
        $this->assertEquals('property', $this->property->getId());
        $this->assertEquals('integer', gettype($this->property->get()));
        $this->assertEquals(123, $this->property->get());

        $this->property = new Property(1.23);
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->property);
        $this->assertEquals('property', $this->property->getId());
        $this->assertEquals('double', gettype($this->property->get()));
        $this->assertEquals(1.23, $this->property->get());

        $this->property = new Property(false);
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->property);
        $this->assertEquals('property', $this->property->getId());
        $this->assertEquals('boolean', gettype($this->property->get()));
        $this->assertEquals(false, $this->property->get());

        $this->property = new Property(null);
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->property);
        $this->assertEquals('property', $this->property->getId());
        $this->assertEquals('NULL', gettype($this->property->get()));
        $this->assertNull($this->property->get());
    }

    /**
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Member\Member::getId
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::get
     */
    public function testConstructArray()
    {
        $this->property = new Property(array(1, false, 'c'));
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->property);
        $this->assertEquals('property', $this->property->getId());
        $value = $this->property->get();
        $this->assertEquals('array', gettype($value));
        $this->assertEquals(3, count($value));
        $this->assertEquals(1, $value[0]);
        $this->assertEquals(false, $value[1]);
        $this->assertEquals('c', $value[2]);
    }

    /**
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Member\Member::getId
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::get
     */
    public function testConstructArrayWithArrayAccess()
    {
        $this->property = new Property(array(1, false, 'c'));
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->property);
        $this->assertEquals('property', $this->property->getId());
        $value = $this->property->get();
        $this->assertEquals('array', gettype($value));
        $this->assertEquals(3, count($value));
        $this->assertEquals(1, $this->property[0]->get());
        $this->assertEquals(false, $this->property[1]->get());
        $this->assertEquals('c', $this->property[2]->get());
    }

    /**
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Member\Member::getId
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::get
     */
    public function testConstructObject()
    {
        $this->property = new Property(new DateTime('01/01/2015'));
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $this->property);
        $this->assertEquals('property', $this->property->getId());
        $value = $this->property->get();
        $this->assertInstanceOf('DateTime', $value);
        $this->assertEquals('01/01/2015', $value->format('d/m/Y'));
    }

    /**
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Member\Member::getId
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Property::set
     */
    public function testConstructResource()
    {
        $fp = @fopen('./composer.json', 'r');
        if ($fp) {
            $this->setExpectedException('\Exception', 'Invalid data-type "resource" for Fluid property');
            $this->property = new Property($fp);
        } else {
            // File not open
            $this->assertEquals(1, 1);
        }
    }

    /**
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Storage::string
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::__toString
     */
    public function testToString()
    {
        $this->expectOutputString('abc1231,false,c[object]hello');
        echo $this->property;
        $this->property = new Property(123);
        echo $this->property;
        $this->property = new Property(array(1, false, 'c'));
        echo $this->property;
        $this->property = new Property(new DateTime('01/01/2015'));
        echo $this->property;
        $this->property = new Property(new Property('hello'));
        echo $this->property;
        $this->property = new Property(NULL);
        echo $this->property;
    }

    /**
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::isCallable
     */
    public function testIsCallable()
    {
        $this->assertFalse($this->property->isCallable());
    }

    /**
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::get
     * @covers Fluidity\Fluid\Member\Property::__clone
     */
    public function testClone()
    {
        $property2 = clone $this->property;
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $property2);
        $this->property->set('hello');
        $this->assertEquals('hello', $this->property);
        $this->assertEquals('abc', $property2);
    }

    /**
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::__construct
     * @covers Fluidity\Fluid\Fluid::__construct
     * @covers Fluidity\Fluid\ArrayAccess::__construct
     * @covers Fluidity\Fluid\Iterator::__construct
     * @covers Fluidity\Fluid\Storage::__construct
     * @covers Fluidity\Fluid\Storage::set
     * @covers Fluidity\Fluid\Storage::get
     * @covers Fluidity\Fluid\Member\Property::set
     * @covers Fluidity\Fluid\Member\Property::get
     * @covers Fluidity\Fluid\Member\Property::sync
     */
    public function testSync()
    {
        $mock = new MockPropertyTest('hello');
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $mock);
        $this->assertNull($mock->get());
        $this->assertEquals('hello', $mock->test1());
        $this->assertNull($mock->test2());
        $mock['test'] = 'world';
        $this->assertEquals('world', $mock->test);
        $this->assertEquals('world', $mock->test1());
        $this->assertEquals('world', $mock->test2());
    }


} 