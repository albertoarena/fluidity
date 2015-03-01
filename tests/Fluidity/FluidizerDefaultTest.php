<?php
use Fluidity\Fluidizer;
use Fluidity\FluidizerDefault;
use Fluidity\Fluid\Base;


class Mock extends Base
{
    protected $name, $surname;

    /**
     * @param mixed $name
     * @param mixed $surname
     */
    public function __construct($name, $surname)
    {
        $this->name = $name;
        $this->surname = $surname;
        parent::__construct(new \Fluidity\Fluid\Settings());
    }

    public function label1()
    {
        // Original properties
        return $this->name . ' ' . $this->surname;
    }

    public function label2()
    {
        // Magic properties
        return $this['name'] . ' ' . $this['surname'];
    }
}


class FluidizerDefaultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mock
     */
    protected $mock;

    protected function setUp()
    {
        FluidizerDefault::reset();
        $this->mock = new Mock('John', 'Smith');
        $this->mock->address = array('1', 'London Road', 'London');
        $this->mock->phone = 7771234567;
        $this->mock->sayHello = function ($obj) {
            return 'Hello ' . $obj->name . ' ' . $obj->surname;
        };
    }

    /**
     * Only for code coverage
     *
     * @covers Fluidity\Fluid\Base::__construct
     * @covers Fluidity\FluidizerDefault::reset
     * @covers Fluidity\FluidizerDefault::create
     */
    public function testDoubleCreate()
    {
        FluidizerDefault::create();
        FluidizerDefault::create();
        $this->assertEquals(1, 1);
    }

    /**
     * @covers Fluidity\Fluid\Base::__construct
     * @covers Fluidity\FluidizerDefault::reset
     * @covers Fluidity\FluidizerDefault::create
     * @covers Fluidity\Fluidizer::define
     */
    public function testFluidMethodJson()
    {
        $this->expectOutputString('John Smith lives at 1,London Road,London, phone 7771234567');
        echo $this->mock->name . ' ' . $this->mock->surname . ' lives at ' . $this->mock->address . ', phone ' . $this->mock->phone;
        $this->assertEquals('"John"', $this->mock->name->json());
        $this->assertEquals('"Smith"', $this->mock->surname->json());
        $this->assertEquals('["1","London Road","London"]', $this->mock->address->json());
        $this->assertEquals('7771234567', $this->mock->phone->json());
        /*
        $this->assertEquals('Hello John Smith', $this->mock->sayHello());
        $this->assertEquals('John Smith', $this->mock->label1());
        $this->assertEquals('John Smith', $this->mock->label2());
        */
    }

    /**
     * @covers Fluidity\Fluid\Base::__construct
     * @covers Fluidity\FluidizerDefault::reset
     * @covers Fluidity\FluidizerDefault::create
     * @covers Fluidity\Fluidizer::define
     */
    public function testFluidMethodType()
    {
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $this->mock->name);
        $this->assertEquals('string', $this->mock->name->type());
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $this->mock->surname);
        $this->assertEquals('string', $this->mock->surname->type());
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $this->mock->phone);
        $this->assertEquals('integer', $this->mock->phone->type());
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $this->mock->address);
        $this->assertEquals('array', $this->mock->address->type());
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Method', $this->mock->sayHello);
        $this->assertEquals('object', $this->mock->sayHello->type());
        $this->assertInstanceOf('\Closure', $this->mock->sayHello->get());
    }

    /**
     * @covers Fluidity\Fluid\Base::__construct
     * @covers Fluidity\FluidizerDefault::reset
     * @covers Fluidity\FluidizerDefault::create
     * @covers Fluidity\Fluidizer::define
     */
    public function testFluidMethodLength()
    {
        $this->assertEquals(4, $this->mock->name->length());
        $this->assertEquals(5, $this->mock->surname->length());
        $this->assertEquals(10, $this->mock->phone->length());
        $this->assertEquals(3, $this->mock->address->length());
        $this->assertEquals(0, $this->mock->sayHello->length());
    }


    /**
     * @covers Fluidity\Fluid\Base::__construct
     * @covers Fluidity\FluidizerDefault::reset
     * @covers Fluidity\FluidizerDefault::create
     * @covers Fluidity\Fluidizer::define
     */
    public function testFluidMethodClone()
    {
        $this->mock->name2 = $this->mock->name->clone();
        $this->mock->name2->set('Bill');
        $this->assertEquals('"John"', $this->mock->name->json());
        $this->assertEquals('"Bill"', $this->mock->name2->json());
    }

} 