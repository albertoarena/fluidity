<?php
use \Fluidity\Fluid\Base;
use \Fluidity\Fluid\Settings;


class MockBase extends Base
{
    protected $something;

    /**
     * @param string $v
     * @param Settings $settings
     */
    public function __construct($v, Settings $settings)
    {
        $this->something = $v;
        parent::__construct($settings);
    }

    public function something()
    {
        return $this->something;
    }

}

class BaseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \Fluidity\Fluid\Base::__construct
     * @covers \Fluidity\Fluid\Base::fluids
     * @covers \Fluidity\Fluid\Base::addNativeProperties
     * @covers \Fluidity\Fluid\Base::getObjectProperties
     * @covers \Fluidity\Fluid\Base::applySettings
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers \Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers \Fluidity\Fluid\Iterator::__construct
     * @covers \Fluidity\Fluid\Storage::__construct
     * @covers \Fluidity\Fluid\Storage::set
     * @covers \Fluidity\Fluid\Settings::__construct
     */
    public function testEmptyConstruct()
    {
        $obj = new Base(new Settings());
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Base', $obj);
    }

    /**
     * @covers \Fluidity\Fluid\Base::__construct
     * @covers \Fluidity\Fluid\Base::fluids
     * @covers \Fluidity\Fluid\Base::addNativeProperties
     * @covers \Fluidity\Fluid\Base::getObjectProperties
     * @covers \Fluidity\Fluid\Base::applySettings
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers \Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers \Fluidity\Fluid\Iterator::__construct
     * @covers \Fluidity\Fluid\Storage::__construct
     * @covers \Fluidity\Fluid\Storage::set
     * @covers \Fluidity\Fluid\Settings::__construct
     * @covers \Fluidity\Fluid\Settings::option
     */
    public function testConstructWithSettingsArray()
    {
        $settings = new Settings(array(
            Settings::METHODS => array(
                'test' => function () {
                        return 'test';
                    }
            ),
            Settings::PROPERTIES => array(
                'hello' => 'hello'
            ),
            'dummy' => null
        ));

        $obj = new Base($settings);
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Base', $obj);
        $this->assertEquals('test', $obj->test());
        $this->assertEquals('hello', $obj->hello);
    }

    /**
     * @covers \Fluidity\Fluid\Base::__construct
     * @covers \Fluidity\Fluid\Base::fluids
     * @covers \Fluidity\Fluid\Base::addNativeProperties
     * @covers \Fluidity\Fluid\Base::getObjectProperties
     * @covers \Fluidity\Fluid\Base::applySettings
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers \Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers \Fluidity\Fluid\Iterator::__construct
     * @covers \Fluidity\Fluid\Storage::__construct
     * @covers \Fluidity\Fluid\Storage::set
     * @covers \Fluidity\Fluid\Settings::__construct
     * @covers \Fluidity\Fluid\Settings::option
     */
    public function testConstructWithSettings()
    {
        $settings = new Settings();
        $settings
            ->option(Settings::METHODS, array(
                'test' => function () {
                        return 'test';
                    }
            ))
            ->option(Settings::PROPERTIES, array(
                'hello' => 'hello'
            ))
            ->option('dummy', null);

        $obj = new Base($settings);
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Base', $obj);
        $this->assertEquals('test', $obj->test());
        $this->assertEquals('hello', $obj->hello);
    }

    /**
     * @covers \Fluidity\Fluid\Base::__construct
     * @covers \Fluidity\Fluid\Base::fluids
     * @covers \Fluidity\Fluid\Base::addNativeProperties
     * @covers \Fluidity\Fluid\Base::getObjectProperties
     * @covers \Fluidity\Fluid\Base::applySettings
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers \Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers \Fluidity\Fluid\Iterator::__construct
     * @covers \Fluidity\Fluid\Storage::__construct
     * @covers \Fluidity\Fluid\Storage::set
     * @covers \Fluidity\Fluid\Settings::__construct
     */
    public function testConstructWithNativePropertiesAndMethods()
    {
        $obj = new MockBase('hiya', new Settings());
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Base', $obj);
        $this->assertEquals('hiya', $obj->something());
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Member\\Property', $obj->something);
        $this->assertEquals('hiya', $obj->something->toFlat());
    }

    /**
     * @covers \Fluidity\Fluid\Base::__construct
     * @covers \Fluidity\Fluid\Base::fluids
     * @covers \Fluidity\Fluid\Base::addNativeProperties
     * @covers \Fluidity\Fluid\Base::getObjectProperties
     * @covers \Fluidity\Fluid\Base::applySettings
     * @covers \Fluidity\Fluid\Base::__toString
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers \Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers \Fluidity\Fluid\Iterator::__construct
     * @covers \Fluidity\Fluid\Storage::__construct
     * @covers \Fluidity\Fluid\Storage::set
     * @covers \Fluidity\Fluid\Settings::__construct
     */
    public function testToString()
    {
        $obj = new Base((new Settings())
            ->option(Settings::METHODS, array(
                'test' => function () {
                        return 'test';
                    }
            ))
            ->option(Settings::PROPERTIES, array(
                'hello' => 'hello'
            )));
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Base', $obj);
        $this->expectOutputString('[method],hello');
        echo $obj;
    }

    /**
     * @covers \Fluidity\Fluid\Base::__construct
     * @covers \Fluidity\Fluid\Base::fluids
     * @covers \Fluidity\Fluid\Base::addNativeProperties
     * @covers \Fluidity\Fluid\Base::getObjectProperties
     * @covers \Fluidity\Fluid\Base::applySettings
     * @covers \Fluidity\Fluid\Base::toFlat
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers \Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers \Fluidity\Fluid\Iterator::__construct
     * @covers \Fluidity\Fluid\Storage::__construct
     * @covers \Fluidity\Fluid\Storage::set
     * @covers \Fluidity\Fluid\Settings::__construct
     */
    public function testToFlat()
    {
        $obj = new Base((new Settings())
            ->option(Settings::METHODS, array(
                'test' => function () {
                        return 'test';
                    }
            ))
            ->option(Settings::PROPERTIES, array(
                'hello' => 'hello'
            )));
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Base', $obj);
        $flat = $obj->toFlat();
        $this->assertEquals(2, count($flat));
        $this->assertArrayHasKey('test', $flat);
        $this->assertArrayHasKey('hello', $flat);
    }

    /**
     * @covers \Fluidity\Fluid\Base::__construct
     * @covers \Fluidity\Fluid\Base::fluids
     * @covers \Fluidity\Fluid\Base::addNativeProperties
     * @covers \Fluidity\Fluid\Base::getObjectProperties
     * @covers \Fluidity\Fluid\Base::applySettings
     * @covers \Fluidity\Fluid\Base::__clone
     * @covers \Fluidity\Fluid\Fluid::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::__construct
     * @covers \Fluidity\Fluid\ArrayAccess::offsetGet
     * @covers \Fluidity\Fluid\ArrayAccess::offsetSet
     * @covers \Fluidity\Fluid\Iterator::__construct
     * @covers \Fluidity\Fluid\Storage::__construct
     * @covers \Fluidity\Fluid\Storage::set
     * @covers \Fluidity\Fluid\Settings::__construct
     */
    public function testClone()
    {
        $obj = new Base((new Settings())
            ->option(Settings::METHODS, array(
                'test' => function () {
                        return 'test';
                    }
            ))
            ->option(Settings::PROPERTIES, array(
                'hello' => 'hello'
            )));
        $this->assertInstanceOf('\\Fluidity\\Fluid\\Base', $obj);
        $this->assertEquals(1, count($obj->properties()));
        $this->assertEquals(1, count($obj->methods()));

        $obj2 = clone $obj;
        $this->assertEquals(1, count($obj2->properties()));
        $this->assertEquals(1, count($obj2->methods()));

        $obj2['test'] = function () {
            return 'test!';
        };
        $obj2['test2'] = function () {
            return null;
        };
        $obj2['hello'] = 'hello!';
        $obj2['hello2'] = 'hello2';
        $obj2['hello3'] = 'hello3';

        $this->assertEquals(1, count($obj->properties()));
        $this->assertEquals(1, count($obj->methods()));
        $this->assertEquals(3, count($obj2->properties()));
        $this->assertEquals(2, count($obj2->methods()));

        //var_dump($obj['test'] === $obj2['test']);

        $this->assertEquals('test', $obj->test());
        $this->assertEquals('test!', $obj2->test());
        $this->assertEquals('hello', $obj->hello);
        $this->assertEquals('hello!', $obj2->hello);
    }
} 