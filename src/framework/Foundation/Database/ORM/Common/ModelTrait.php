<?php
namespace Laventure\Foundation\Database\ORM\Common;


use Laventure\Foundation\Database\ORM\Exception\ModelException;



/**
 * @ModelTrait
*/
trait ModelTrait
{

    /**
     * @var array
     */
    protected $attributes = [];




    /**
     * @var array
     */
    protected $fillable = [];





    /**
     * @var array
     */
    protected $hidden = [];




    /**
     * @var string[]
    */
    protected $guarded = ['id'];




    /**
     * @param $field
     * @param $value
    */
    public function setAttribute($field, $value)
    {
        $this->attributes[$field] = $value;
    }




    /**
     * @param $field
     * @return bool
     */
    public function hasAttribute($field): bool
    {
        return isset($this->attributes[$field]);
    }




    /**
     * @param $field
    */
    public function removeAttribute($field)
    {
        if($this->hasAttribute($field))
        {
            unset($this->attributes[$field]);
        }
    }



    /**
     * @param $field
     * @return mixed
    */
    public function getAttribute($field)
    {
        return $this->attributes[$field] ?? null;
    }






    /**
     * @return mixed
     * @throws ModelException
    */
    private function getTableColumns()
    {
         throw new ModelException(__METHOD__ . " must be implemented.");
    }



    /**
     * @param $field
     * @param $value
    */
    public function __set($field, $value)
    {
        $this->setAttribute($field, $value);
    }



    /**
     * @param $field
     * @return mixed
    */
    public function __get($field)
    {
        return $this->getAttribute($field);
    }



    /**
     * @param mixed $offset
     * @return bool
    */
    public function offsetExists($offset)
    {
        return $this->hasAttribute($offset);
    }


    /**
     * @param mixed $offset
     * @return mixed|void
    */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }


    /**
     * @param mixed $offset
     * @param mixed $value
    */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }



    /**
     * @param mixed $offset
    */
    public function offsetUnset($offset)
    {
        $this->removeAttribute($offset);
    }

}