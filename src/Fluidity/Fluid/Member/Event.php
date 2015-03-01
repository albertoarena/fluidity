<?php
/**
 * This class defines the Member/Event class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity\Fluid\Member;


class Event extends Member
{
    /** @internal */
    const ID = 'event';

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
            throw new \Exception('Invalid value for a Fluid event');
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
        return '[event]';
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