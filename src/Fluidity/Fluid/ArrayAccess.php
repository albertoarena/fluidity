<?php
/**
 * This class defines the ArrayAccess class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity\Fluid;


abstract class ArrayAccess extends Iterator implements \ArrayAccess
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->fluids->get(self::MEMBERS));
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        $members = $this->fluids->get(self::MEMBERS);
        if (array_key_exists($offset, $members)) {
            return $members[$offset];
        }
        return null;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $members = & $this->fluids->get(self::MEMBERS);
        if (!array_key_exists($offset, $members)) {
            $members[$offset] = MemberFactory::createMember($value);
        } else {
            $members[$offset]->set($value);
        }
        if (property_exists($this, $offset)) {
            $this->$offset = $value;
            $members[$offset]->sync($this->$offset);
        }
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $members = & $this->fluids->get(self::MEMBERS);
        if (array_key_exists($offset, $members)) {
            unset($members[$offset]);
            if (property_exists($this, $offset)) {
                $this->$offset = null;
            }
        }
    }

    /**
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @param mixed $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * @param mixed $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * @param mixed $name
     */
    public function __unset($name)
    {
        $this->offsetUnset($name);
    }

} 