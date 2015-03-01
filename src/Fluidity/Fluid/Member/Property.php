<?php
/**
 * This class defines the Member/Property class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity\Fluid\Member;


class Property extends Member
{
    /** @internal */
    const ID = 'property';

    /** @internal */
    const TYPE = 'var_type';

    /** @internal */
    const VAR_SCALAR = 'scalar';

    /** @internal */
    const VAR_ARRAY = 'array';

    /** @internal */
    const VAR_OBJECT = 'object';

    /** @internal */
    const SYNC = 'sync';

    /**
     * @param mixed $value
     */
    public function __construct($value = null)
    {
        parent::__construct(self::ID);
        $this->fluids()->set(self::TYPE, null);
        $this->fluids()->set(self::SYNC, null);
        $this->set($value);
    }

    /**
     * @param $value
     * @return mixed|void
     * @throws \Exception
     */
    public function set($value)
    {
        switch (true) {
            case is_array($value):
                $this->fluids()->set(self::ID, $value);
                $this->fluids()->set(self::TYPE, self::VAR_ARRAY);
                $members = & $this->fluids->get(self::MEMBERS);
                foreach ($value as $key => $innerValue) {
                    $members[$key] = new Property($innerValue);
                }
                break;

            case is_object($value):
                $this->fluids()->set(self::ID, $value);
                $this->fluids()->set(self::TYPE, self::VAR_OBJECT);
                break;

            case is_resource($value):
                throw new \Exception('Invalid data-type "resource" for Fluid property');
                break;

            default:
                $this->fluids()->set(self::ID, $value);
                $this->fluids()->set(self::TYPE, self::VAR_SCALAR);
                if ($this->fluids()->get(self::SYNC) !== null) {
                    $this->fluids()->set(self::SYNC, $value);
                }
                break;
        }
    }

    /**
     * @return array|mixed
     */
    public function get()
    {
        //$ret = $this->value();
        $ret = $this->toFlat();
        if ($this->fluids()->get(self::TYPE) === self::VAR_ARRAY) {
            $ret = $this->fluids()->flat($ret);
        }
        return $ret;
    }

    /**
     * @return boolean
     */
    public function isCallable()
    {
        return false;
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
        //$ret = $this->value();
        $ret = $this->toFlat();
        switch ($this->fluids()->get(self::TYPE)) {
            case self::VAR_ARRAY:
                $ret = $this->fluids()->string($ret);
                break;

            case self::VAR_OBJECT:
                if (method_exists($ret, '__toString')) {
                    $ret = $ret->__toString();
                } else {
                    $ret = '[object]';
                }
                break;

            default:
                $ret = $this->fluids()->string($ret);
                break;
        }
        return $ret;
    }

    /**
     * @param mixed $property
     */
    public function sync(&$property)
    {
        $this->fluids()->set(self::SYNC, $property);
    }
}