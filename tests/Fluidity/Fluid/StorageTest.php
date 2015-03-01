<?php
use \Fluidity\Fluid\Storage;
use \Fluidity\Fluid\Member\Property;
use \Fluidity\Fluid\Member\Method;

class MockObj
{
    public function __toString()
    {
        return 'mock';
    }
}

class FluidStorageTest extends \PHPUnit_Framework_TestCase
{

    /** @var \Fluidity\Fluid\Storage */
    protected $storage;

    public function setUp()
    {
        $this->storage = new Storage();
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::getUniqueId
     */
    public function testGetUniqueId()
    {
        $storage2 = new Storage();
        $this->assertNotEquals($this->storage->getUniqueId(), $storage2->getUniqueId());
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::set
     * \Fluidity\Fluid\Storage::get
     */
    public function testSetGet()
    {
        $this->storage->set('test', 123);
        $this->assertEquals(123, $this->storage->get('test'));
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::set
     * \Fluidity\Fluid\Storage::get
     */
    public function testGetByReference()
    {
        $this->storage->set('test', 123);
        $a = & $this->storage->get('test');
        $this->assertEquals(123, $a);
        $a = 'abc';
        $this->assertEquals('abc', $this->storage->get('test'));
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::get
     */
    public function testNotExistingKey()
    {
        $this->setExpectedException('\Exception', 'Not-existing fluid storage: dummy');
        $this->storage->get('dummy');
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::set
     * \Fluidity\Fluid\Storage::exist
     */
    public function testExist()
    {
        $this->storage->set('test', 123);
        $this->assertTrue($this->storage->exist('test'));
        $this->assertFalse($this->storage->exist('dummy'));
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::set
     * \Fluidity\Fluid\Storage::flat
     */
    public function testFlat()
    {
        $this->assertEquals(123, $this->storage->flat(123));
        $this->assertEquals(4.56, $this->storage->flat(4.56));
        $this->assertEquals('abc', $this->storage->flat('abc'));
        $this->assertFalse($this->storage->flat(false));
        $this->assertEquals(array(1, 'a'), $this->storage->flat(array(1, 'a')));
        $this->assertEquals(array(1, 'a'), $this->storage->flat(array(new Property('1'), new Property('a'))));
        $this->assertInstanceOf('DateTime', $this->storage->flat(new DateTime('01/01/2015')));
        $this->assertEquals('hello world', $this->storage->flat(new Property('hello world')));
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::set
     * \Fluidity\Fluid\Storage::string
     */
    public function testString()
    {
        $this->assertEquals('123', $this->storage->string(123));
        $this->assertEquals('4.56', $this->storage->string(4.56));
        $this->assertEquals('abc', $this->storage->string('abc'));
        $this->assertEquals('true', $this->storage->string(true));
        $this->assertEquals('false', $this->storage->string(false));
        $this->assertEquals('1,a', $this->storage->string(array(1, 'a')));
        $this->assertEquals('1,a', $this->storage->string(array(new Property('1'), new Property('a'))));
        $this->assertEquals('mock', $this->storage->string(new MockObj()));
        $this->assertEquals('[object]', $this->storage->string(new DateTime('01/01/2015')));
        $this->assertEquals('hello world', $this->storage->string(new Property('hello world')));
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::set
     * \Fluidity\Fluid\Storage::getAsArray
     * \Fluidity\Fluid\Storage::__call
     */
    public function testGetAsArray()
    {
        $this->storage->set('test', array(1, 2, 'c'));
        $array = $this->storage->test();
        $this->assertTrue(is_array($array));
        $this->assertEquals(1, $array[0]);
        $this->assertEquals(2, $array[1]);
        $this->assertEquals('c', $array[2]);
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::set
     * \Fluidity\Fluid\Storage::getFlatAsArray
     * \Fluidity\Fluid\Storage::__call
     */
    public function testGetFlatAsArray()
    {
        $this->storage->set('test', array(1, 2, 'c'));
        $array = $this->storage->flatTest();
        $this->assertTrue(is_array($array));
        $this->assertEquals(1, $array[0]);
        $this->assertEquals(2, $array[1]);
        $this->assertEquals('c', $array[2]);
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::set
     * \Fluidity\Fluid\Storage::__call
     */
    public function testInvalidCall()
    {
        $this->setExpectedException('\Exception', 'Invalid fluid storage: dummy');
        $this->storage->dummy();
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::set
     * \Fluidity\Fluid\Storage::get
     * \Fluidity\Fluid\Storage::deepClone
     * \Fluidity\Fluid\Storage::__clone
     */
    public function testClone()
    {
        $this->storage->set('test1', 'abc');
        $this->storage->set('test2', array(1, 2, 'c'));
        $this->storage->set('test3', new DateTime());
        $storage2 = clone $this->storage;
        $storage2->set('test1', 'abc');
        $storage2->set('test2', array(1, 2, 'c'));
        $this->assertEquals('abc', $this->storage->get('test1'));
        $this->assertTrue(is_array($this->storage->get('test2')));
        $this->assertTrue(is_object($this->storage->get('test3')));
        $this->assertEquals('abc', $storage2->get('test1'));
        $this->assertTrue(is_array($storage2->get('test2')));
        $storage2->set('test1', 123);
        $storage2->set('test2', new DateTime());
        $storage2->set('test3', 'abc');
        $this->assertEquals('abc', $this->storage->get('test1'));
        $this->assertTrue(is_array($this->storage->get('test2')));
        $this->assertTrue(is_object($this->storage->get('test3')));
        $this->assertEquals(123, $storage2->get('test1'));
        $this->assertTrue(is_object($storage2->get('test2')));
        $this->assertEquals('abc', $storage2->get('test3'));
    }

    /**
     * \Fluidity\Fluid\Storage::__construct
     * \Fluidity\Fluid\Storage::set
     * \Fluidity\Fluid\Storage::get
     * \Fluidity\Fluid\Storage::deepClone
     * \Fluidity\Fluid\Storage::__clone
     */
    public function testDeepCloneArray()
    {
        $data = array(
            'a' => 123,
            'b' => 'abc',
            'c' => new Method(function () {
                    return 'c';
                }),
            'd' => array(
                'e' => new Method(function () {
                        return 'e';
                    }),
                'f' => array(
                    'g' => new Method(function () {
                                return 'g';
                            }
                        )
                )
            )
        );
        $this->storage->set('data', $data);
        $this->assertEquals('array', gettype($this->storage->get('data')));
        $this->assertEquals('123', $this->storage->get('data')['a']);
        $this->assertEquals('abc', $this->storage->get('data')['b']);
        $this->assertEquals('c', $this->storage->get('data')['c']->call());
        $this->assertEquals('e', $this->storage->get('data')['d']['e']->call());
        $this->assertEquals('g', $this->storage->get('data')['d']['f']['g']->call());

        $storage2 = clone $this->storage;
        $data = & $storage2->get('data');
        $data['c'] = new Method(function () {
            return 'c!';
        });
        $data['d']['e'] = 'e!';

        $this->assertEquals('array', gettype($this->storage->get('data')));
        $this->assertEquals('123', $this->storage->get('data')['a']);
        $this->assertEquals('abc', $this->storage->get('data')['b']);
        $this->assertEquals('c', $this->storage->get('data')['c']->call());
        $this->assertEquals('e', $this->storage->get('data')['d']['e']->call());
        $this->assertEquals('g', $this->storage->get('data')['d']['f']['g']->call());

        $this->assertEquals('array', gettype($storage2->get('data')));
        $this->assertEquals('123', $storage2->get('data')['a']);
        $this->assertEquals('abc', $storage2->get('data')['b']);
        $this->assertEquals('c!', $storage2->get('data')['c']->call());
        $this->assertEquals('e!', $storage2->get('data')['d']['e']);
        $this->assertEquals('g', $storage2->get('data')['d']['f']['g']->call());
    }

} 