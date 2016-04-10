<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Match extends Model
{
    protected $table = 'matches';

    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param integer $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    public function incrementCount()
    {
        $this->count++;
    }

    /**
     * @return HasMany
     */
    public function odds()
    {
        return $this->hasMany('App\Models\Odd');
    }

    /**
     * @return static
     */
    public static function make()
    {
        return new static();
    }
}
