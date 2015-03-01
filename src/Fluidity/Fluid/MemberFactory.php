<?php
/**
 * This class defines the MemberFactory class of Fluidity.
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

class MemberFactory
{
    /**
     * @param mixed $value
     * @param bool $isEvent                 If true and $value is an object, create an Event
     * @return Event|Method|Property|null
     */
    public static function createMember($value, $isEvent = false)
    {
        switch (true) {
            case is_callable($value):
                if ($isEvent) {
                    return new Event($value);
                } else {
                    return new Method($value);
                }
                break;

            case $value instanceof Member:
                return $value;
                break;

            case is_scalar($value):
            case is_object($value):
            case is_array($value):
            case is_null($value):
                return new Property($value);
                break;

            default:
                return null;
                break;
        }
    }
} 