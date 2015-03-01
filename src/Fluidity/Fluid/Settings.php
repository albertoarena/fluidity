<?php
/**
 * This class defines the Settings class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity\Fluid;


class Settings
{
    /** @internal */
    //const WRAP_OBJECT = 100;

    /** @internal */
    const PROPERTIES = 101;

    /** @internal */
    const METHODS = 102;

    /** @var array */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->options = array();

        // Set options
        if (is_array($options)) {
            foreach ($options as $name => $value) {
                $this->option($name, $value);
            }
        }
    }

    /**
     * @param $name
     * @param null $value
     * @return $this|null|\Fluidity\Fluid\Settings
     */
    public function option($name, $value = null)
    {
        if ($value !== null) {
            $this->options[$name] = $value;
            return $this;
        } else if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        } else {
            return null;
        }
    }
}