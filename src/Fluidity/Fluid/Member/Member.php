<?php
/**
 * This class defines the Member/Member class of Fluidity.
 *
 * @package     Fluidity
 * @author      Alberto Arena <arena.alberto@gmail.com>
 * @copyright   Alberto Arena <arena.alberto@gmail.com>
 * @license     https://github.com/albertoarena/fluidity/blob/master/LICENCE  MIT, BSD and GPL
 * @link        http://albertoarena.co.uk/fluidity
 *
 */
namespace Fluidity\Fluid\Member;


use Fluidity\Fluid\Fluid;

abstract class Member extends Fluid
{

    /** @var string ID or data type that identifies the object in the Storage */
    protected $id;

    /**
     * @param $id
     */
    public function __construct($id)
    {
        parent::__construct();
        $this->id = $id;
        $this->fluids()->set($id, null);
    }

    /**
     * Get current ID that identifies the object in the Storage
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Check if the member is callable (method, event) or not
     *
     * @return boolean
     */
    abstract public function isCallable();

    /**
     * Set a new value for the member
     *
     * @param $value
     * @return mixed
     */
    abstract public function set($value);

    /**
     * Get che current value of the member
     * Wrapper to toFlat() method
     *
     * @return mixed
     */
    public function get()
    {
        return $this->toFlat();
    }

    /**
     * Get the original value stored in the member
     *
     * @return mixed
     */
    public function toFlat()
    {
        return $this->fluids()->get($this->id);
    }

} 