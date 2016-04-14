<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Odd extends Model
{
    protected $table = 'odds';

    protected $guarded = ['id'];

    public $timestamps = false;

    static $ODD_IDS = [
        1 => [1,2,3],
        2 => [1,2,3],
        5 => [1,2,3,4,5,6,7,8,9]
    ];

    static $ODD_NAMES = [
        1 => [
            1 => '1',
            2 => 'X',
            3 => '2'
        ],
        2 => [
            1 => '1X',
            2 => '12',
            3 => 'X2'
        ],
        5 => [
            1 => '1-1',
            2 => '1-X',
            3 => '1-2',
            4 => 'X-1',
            5 => 'X-X',
            6 => 'X-2',
            7 => '2-1',
            8 => '2-X',
            9 => '2-2',
        ]
    ];

    /**
     * @param $odd
     *
     * @return bool
     */
    public static function checkOdd($odd)
    {
        return array_key_exists($odd, self::$ODD_IDS);
    }

    public static function hasAllOdds($odd)
    {
        $x12 = $odd[0];
        $x112x2 = $odd[1];
        $htft = $odd[4];

        $result = [];

        foreach($x12->subgames as $i){
            if(property_exists($i, 'winStatus') && $i->winStatus === "WIN") $result[] = $i;
        }

        foreach($x112x2->subgames as $i){
            if(property_exists($i, 'winStatus') && $i->winStatus === "WIN") $result[] = $i;
        }

        foreach($htft->subgames as $i){
            if(property_exists($i, 'winStatus') && $i->winStatus === "WIN") $result[] = $i;
        }

        return (count($result) >= 4) ? true : false;

    }

    /**
     * @param $odd
     * @param $subgame
     *
     * @return bool
     */
    public static function checkSubGame($odd, $subgame)
    {
        if(in_array($subgame, self::$ODD_IDS[$odd])){
            return $odd."|".$subgame;
        }
        return false;
    }

    public static function getNameByCategory($categories, $sub)
    {
        return $categories[$sub]->name;
    }

    /**
     * @return integer
     */
    public function getWinCount()
    {
        return $this->win_count;
    }

    /**
     * @param integer $win_count
     */
    public function setWinCount($win_count)
    {
        $this->win_count = $win_count;
    }

    public function incrementWinCount()
    {
        $this->win_count++;
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
     * @return integer
     */
    public function getMatchId()
    {
        return $this->match_id;
    }

    /**
     * @param integer $match_id
     */
    public function setMatchId($match_id)
    {
        $this->match_id = $match_id;
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
     *
     * @return static
     */
    public static function make($name, $category, $value)
    {
        return new static([
            'name'       => $name,
            'category'   => $category,
            'value'      => $value,
        ]);
    }
}
