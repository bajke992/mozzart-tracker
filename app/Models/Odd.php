<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Odd extends Model
{
    protected $table = 'odds';

    protected $guarded = ['id'];

    /**
     * @return string
     */
    public function getWinStatus()
    {
        return $this->win_status;
    }

    /**
     * @param string $win_status
     */
    public function setWinStatus($win_status)
    {
        $this->win_status = $win_status;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

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
     * @return BelongsTo
     */
    public function match()
    {
        return $this->belongsTo('App\Models\Match');
    }

    /**
     * @param string $name
     * @param string $category
     * @param string $value
     * @param string $win_status
     *
     * @return static
     */
    public static function make($name, $category, $value, $win_status)
    {
        return new static([
            'name'       => $name,
            'category'   => $category,
            'value'      => $value,
            'win_status' => $win_status
        ]);
    }
}
