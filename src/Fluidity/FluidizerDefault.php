<?php
/**
 * This class defines the FluidizerDefault class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity;


use Fluidity\Fluid\Member\Member;
use Fluidity\Fluid\Member\Property;

class FluidizerDefault
{

    protected static $init = false;

    /**
     * Reset default fluid methods, creating them again
     */
    public static function reset()
    {
        Fluidizer::clear();
        self::$init = false;
        self::create();
    }

    /**
     * Create default fluid methods
     */
    public static function create()
    {
        if (self::$init === true) {
            return;
        }

        // Define a fluid method "json"
        Fluidizer::define('json', function ($object, $arguments) {
            $properties = $object->properties();
            if (empty($properties)) {
                return json_encode($object->get());
            } else {
                return json_encode(array_map(function ($v) {
                    return $v->toFlat();
                }, $properties));
            }
        });

        // Define a fluid method "length"
        Fluidizer::define('type', function ($object, $arguments) {
            return gettype($object->get());
        });

        // Define a fluid method "length"
        Fluidizer::define('length', function ($object, $arguments) {
            $flat = $object->toFlat();
            if (is_array($flat)) {
                return count($flat);
            } else if (is_scalar($flat)) {
                return strlen(strval($flat));
            } else {
                return 0;
            }
        });

        // Define a fluid method "clone"
        Fluidizer::define('clone', function ($object, $arguments) {
            return clone $object;
        });

        self::$init = true;
    }

}
