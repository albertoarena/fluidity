<?php
/**
 * This class defines the Member/Method class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity\Fluid\Member;


class Method extends Member
{
    /** @internal */
    const ID = 'method';

    /**
     * @param callable|\Closure $value
     */
    public function __construct($value)
    {
        parent::__construct(self::ID);
        $this->set($value);
    }

    /**
     * @param callable|\Closure $value
     * @return mixed|void
     * @throws \Exception
     */
    public function set($value)
    {
        if (is_callable($value)) {
            $this->fluids()->set(self::ID, $value);
        } else {
            throw new \Exception('Invalid value for a Fluid method');
        }
    }

    /**
     * @return mixed|void
     */
    public function __clone()
    {
        $this->fluids = clone $this->fluids;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '[method]';
    }

    /**
     * @return boolean
     */
    public function isCallable()
    {
        return true;
    }

    /**
     * @param mixed $args
     * @return mixed
     */
    public function call($args = null)
    {
        return call_user_func_array($this->get(), func_get_args());
    }

}