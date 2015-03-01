<?php
/**
 * This class defines the Fluidizer class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity;

use Fluidity\Fluid\Settings;


class Fluidizer
{
    /** @var array */
    protected static $methods = array();

    /**
     * Define a fluid method (a dynamic method available obliquely to all classes that inherits from FluidClass
     *
     * @param string $name
     * @param callable $callback
     * @throws \Exception
     */
    public static function define($name, $callback)
    {
        if (is_string($name) && strlen($name) > 0 && (is_callable($callback) || $callback instanceof \Closure)) {
            self::$methods[$name] = $callback;
        } else {
            throw new \Exception('Only a callable or closure can be associated to a fluid method');
        }
    }

    /**
     * Un-define a fluid method
     * @param string $name
     */
    public static function undefine($name)
    {
        if (array_key_exists($name, self::$methods)) {
            unset(self::$methods[$name]);
        }
    }

    /**
     * Clear all defined fluid methods
     */
    public static function clear()
    {
        self::$methods = array();
    }

    /**
     * Get a fluid method by name
     *
     * @param string $name
     * @return callable|\Closure|null
     */
    public static function get($name)
    {
        if (array_key_exists($name, self::$methods)) {
            return self::$methods[$name];
        }
        return null;
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return array_keys(self::$methods);
    }

} 