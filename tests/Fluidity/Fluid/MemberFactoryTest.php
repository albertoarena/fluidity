<?php
use \Fluidity\Fluid\MemberFactory;


class MemberFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Method::__construct
     */
    public function testCreateCallable()
    {
        $callback = function ($v) {
            echo 'hello ' . $v;
        };

        $member = MemberFactory::createMember($callback);
        $this->assertNotNull($member);
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Method', $member);
        $this->expectOutputString('hello world');
        $member->call('world');
    }

    /**
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Method::__construct
     * @covers Fluidity\Fluid\Member\Member::get
     */
    public function testCreateCallableEvent()
    {
        $callback = function ($v) {
            echo 'hello ' . $v;
        };

        $member = MemberFactory::createMember($callback, true);
        $this->assertNotNull($member);
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Event', $member);
        $this->expectOutputString('hello world');
        $member->call('world');
    }

    /**
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::get
     */
    public function testCreateScalar()
    {
        $member = MemberFactory::createMember(123);
        $this->assertNotNull($member);
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $member);
        $this->assertEquals(123, $member->get());
        $member = MemberFactory::createMember(1.23);
        $this->assertNotNull($member);
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $member);
        $this->assertEquals(1.23, $member->get());
        $member = MemberFactory::createMember('abc');
        $this->assertNotNull($member);
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $member);
        $this->assertEquals('abc', $member->get());
        $member = MemberFactory::createMember(false);
        $this->assertNotNull($member);
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $member);
        $this->assertEquals(false, $member->get());
    }

    /**
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::get
     */
    public function testCreateArray()
    {
        $member = MemberFactory::createMember(array(1, 2, 3));
        $this->assertNotNull($member);
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $member);
        $this->assertEquals('array', gettype($member->toFlat()));
        $this->assertEquals(array(1, 2, 3), $member->toFlat());
    }

    /**
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::get
     */
    public function testCreateObject()
    {
        $obj = new DateTime();
        $member = MemberFactory::createMember($obj);
        $this->assertNotNull($member);
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $member);
        $this->assertEquals('object', gettype($member->toFlat()));
        $this->assertInstanceOf('DateTime', $member->toFlat());
    }

    /**
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::get
     */
    public function testCreateInstanceOfMember()
    {
        $obj = new \Fluidity\Fluid\Member\Property('abc');
        $member = MemberFactory::createMember($obj);
        $this->assertNotNull($member);
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $member);
        $this->assertEquals('abc', $member->toFlat());
    }

    /**
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::get
     */
    public function testCreateNull()
    {
        $member = MemberFactory::createMember(null);
        $this->assertNotNull($member);
        $this->assertInstanceOf('Fluidity\\Fluid\\Member\\Property', $member);
        $this->assertNull($member->toFlat());
    }

    /**
     * @covers Fluidity\Fluid\MemberFactory::createMember
     * @covers Fluidity\Fluid\Member\Property::__construct
     * @covers Fluidity\Fluid\Member\Member::get
     */
    public function testCreateInvalid()
    {
        $fp = @fopen('./composer.json', 'r');
        if ($fp) {
            $member = MemberFactory::createMember($fp);
            $this->assertNull($member);
            fclose($fp);
        } else {
            // File not open
            $this->assertEquals(1, 1);
        }
    }
} 