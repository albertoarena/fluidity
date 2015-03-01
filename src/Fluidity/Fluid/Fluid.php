<?php
/**
 * This class defines the Fluid class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity\Fluid;


use Fluidity\Fluid\Member\Event;
use Fluidity\Fluid\Member\Member;
use Fluidity\Fluid\Member\Method;
use Fluidity\Fluid\Member\Property;
use Fluidity\Fluidizer;

abstract class Fluid extends ArrayAccess implements \ArrayAccess
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Call a method, according to the following priority:
     * 1) method injected in the object
     * 2) fluid method defined with Fluidizer::define()
     *
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        $members = $this->fluids->get(self::MEMBERS);
        if (array_key_exists($name, $members)) {
            if ($members[$name]->isCallable()) {
                // call injected method
                return call_user_func_array($members[$name]->get(), array_merge(array($this), $arguments));
            } else {
                throw new \Exception('Member ' . $name . ' is not callable');
            }
        } else {
            $callback = Fluidizer::get($name);
            if (!is_null($callback)) {
                // fluid method
                return call_user_func($callback, $this, $arguments);
            } else {
                throw new \Exception('Fluid method ' . $name . ' is not available');
            }
        }
    }

    /**
     * @return array
     */
    public function properties()
    {
        return array_filter($this->fluids()->get(self::MEMBERS), function ($v) {
            return ($v instanceof Property);
        });
    }

    /**
     * @return array
     */
    public function methods()
    {
        return array_filter($this->fluids()->get(self::MEMBERS), function ($v) {
            return ($v instanceof Method);
        });
    }

    /**
     * @return array
     */
    public function events()
    {
        return array_filter($this->fluids()->get(self::MEMBERS), function ($v) {
            return ($v instanceof Event);
        });
    }

    /**
     * @return string
     */
    abstract public function __toString();

    /**
     * @return mixed
     */
    abstract public function toFlat();
}