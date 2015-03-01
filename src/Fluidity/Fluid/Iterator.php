<?php
/**
 * This class defines the Iterator class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity\Fluid;


abstract class Iterator implements \Iterator
{
    /** @internal */
    const MEMBERS = 'members';

    /** @var \Fluidity\Fluid\Storage */
    protected $fluids;

    public function __construct()
    {
        $this->fluids = new Storage();
        $this->fluids->set(self::MEMBERS, array());
    }

    /**
     * @return \Fluidity\Fluid\Storage
     */
    public function fluids()
    {
        return $this->fluids;
    }

    /**
     * @return mixed|void
     */
    public function rewind()
    {
        return reset($this->fluids->get(self::MEMBERS));
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->fluids->get(self::MEMBERS));
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->fluids->get(self::MEMBERS));
    }

    /**
     * @return mixed|void
     */
    public function next()
    {
        return next($this->fluids->get(self::MEMBERS));
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->key() !== null;
    }

    /**
     * @return mixed|void
     */
    public function __clone()
    {
        $this->fluids = clone $this->fluids;
    }

} 