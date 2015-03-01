<?php
/**
 * This class defines the Base class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity\Fluid;

use Fluidity\Fluid\Fluid;
use Fluidity\Fluid\Member\Event;
use Fluidity\Fluid\Member\Member;
use Fluidity\Fluid\Member\Method;
use Fluidity\Fluid\Member\Property;
use Fluidity\Fluid\Settings;

class Base extends Fluid
{
    /** @internal */
    const SETTINGS = 'settings';

    /** @internal */
    const SKIP_PREFIX = 'fluid';

    /**
     * @param \Fluidity\Fluid\Settings $settings
     */
    public function __construct(Settings $settings)
    {
        parent::__construct();
        $this->fluids()->set(self::SETTINGS, $settings);
        $this->addNativeProperties();
        $this->applySettings();
    }

    /**
     * Add native properties of the object to $fluidProperties
     */
    protected function addNativeProperties()
    {
        $properties = $this->getObjectProperties();
        foreach (array_keys($properties) as $property) {
            $this->offsetSet($property, $this->{$property});
        }
    }

    protected function applySettings()
    {
        $settings = $this->fluids()->get(self::SETTINGS);

        if ($methods = $settings->option(Settings::METHODS)) {
            foreach ($methods as $name => $callback) {
                $this->offsetSet($name, $callback);
            }
        }

        if ($properties = $settings->option(Settings::PROPERTIES)) {
            foreach ($properties as $name => $callback) {
                $this->offsetSet($name, $callback);
            }
        }
    }

    /**
     * Get object native properties, skipping those starting with fluid
     *
     * @return array
     */
    protected function getObjectProperties()
    {
        $list = get_object_vars($this);
        return array_filter($list, function ($item) use (&$list) {
            $key = key($list);
            next($list);
            return substr($key, 0, 5) != self::SKIP_PREFIX;
        });
    }

    /**
     * Convert object to string. Return each property separated by a comma
     *
     * @return string
     */
    public function __toString()
    {
        return implode(',', array_map(function ($v) {
            return $v->__toString();
        }, $this->fluids->get(self::MEMBERS)));
    }

    /**
     * @return mixed|void
     */
    public function __clone()
    {
        $this->fluids = clone $this->fluids;
    }

    /**
     * Return flat version of the object
     * @return array
     */
    public function toFlat()
    {
        return $this->fluids()->flatMembers();
    }
}