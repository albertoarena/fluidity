<?php
/**
 * This class defines the Storage class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity\Fluid;


class Storage
{
    /** @var array */
    protected $storage;

    /** @var string */
    protected $uniqueId;

    /** @internal */
    const UNIQUE_ID_SEED = 'Mda:!6"c~rY9ZwQyt+?';

    public function __construct()
    {
        $this->uniqueId = uniqid(self::UNIQUE_ID_SEED, true);
        $this->storage = array();
    }

    /**
     * @return string
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }

    /**
     * Create/Update an item in the storage
     *
     * @param string $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->storage[$key] = $value;
    }

    /**
     * Get a reference to an existing item in the storage
     *
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public function &get($key)
    {
        if (array_key_exists($key, $this->storage)) {
            return $this->storage[$key];
        }
        throw new \Exception('Not-existing fluid storage: ' . $key);
    }

    /**
     * Check if an item exists in the storage
     *
     * @param string $key
     * @return bool
     */
    public function exist($key)
    {
        return array_key_exists($key, $this->storage);
    }

    /**
     * Reduce an object to its flat version
     *
     * @param $obj
     * @return array|mixed
     */
    public function flat($obj)
    {
        if (is_array($obj)) {
            $that = $this;
            return array_map(function ($v) use ($that) {
                return $that->flat($v);
            }, $obj);
        } else if ($obj instanceof Fluid) {
            return $obj->toFlat();
        } else {
            return $obj;
        }
    }

    public function string($obj)
    {
        if (is_array($obj)) {
            $that = $this;
            return implode(',', array_map(function ($v) use ($that) {
                return $that->string($v);
            }, $obj));
        } else if ($obj instanceof Fluid) {
            return $obj->__toString();
        } else if (is_bool($obj)) {
            return ($obj ? 'true' : 'false');
        } else if (is_object($obj)) {
            if (method_exists($obj, '__toString')) {
                return $obj->__toString();
            }
            return '[object]';
        } else {
            return strval($obj);
        }
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function getAsArray($key)
    {
        $list = $this->get($key);
        ksort($list);
        return $list;
    }

    /**
     * @param string $key
     * @return array|mixed
     */
    protected function getFlatAsArray($key)
    {
        $list = $this->flat($this->get($key));
        ksort($list);
        return $list;
    }

    /**
     * @param $name
     * @param $arguments
     * @return array|mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        $isFlat = substr($name, 0, 4) == 'flat';
        $flatName = lcfirst(substr($name, 4));
        if (array_key_exists($name, $this->storage)) {
            return $this->getAsArray($name);
        } else if ($isFlat && array_key_exists($flatName, $this->storage)) {
            return $this->getFlatAsArray($flatName);
        }
        throw new \Exception('Invalid fluid storage: ' . $name);
    }

    /**
     * Deep clone of whatever data type
     *
     * @param mixed $obj
     * @return mixed
     */
    public function deepClone($obj)
    {
        if (is_object($obj)) {
            $obj = clone $obj;
        } else if (is_array($obj)) {
            foreach ($obj as $key => $value) {
                if (is_object($value)) {
                    $obj[$key] = $this->deepClone($value);
                } else if (is_array($value)) {
                    $obj[$key] = $this->deepClone($value);
                }
            }
        }
        return $obj;
    }

    /**
     * @return mixed|void
     */
    public function __clone()
    {
        $this->uniqueId = uniqid(self::UNIQUE_ID_SEED, true);
        $this->storage = $this->deepClone($this->storage);
    }

}