<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Match extends Model
{
    protected $table = 'matches';

    protected $guarded = ['id'];

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
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * @param string $home
     */
    public function setHome($home)
    {
        $this->home = $home;
    }

    /**
     * @return string
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * @param string $visitor
     */
    public function setVisitor($visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * @return string
     */
    public function getCompetitionName()
    {
        return $this->competition_name;
    }

    /**
     * @param string $competition_name
     */
    public function setCompetitionName($competition_name)
    {
        $this->competition_name = $competition_name;
    }

    /**
     * @return string
     */
    public function getCompetitionNameShort()
    {
        return $this->competition_name_short;
    }

    /**
     * @param string $competition_name_short
     */
    public function setCompetitionNameShort($competition_name_short)
    {
        $this->competition_name_short = $competition_name_short;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function setResult($result)
    {
        $this->result = $result;
    }


    public function getMatchStatus()
    {
        return $this->match_status;
    }

    public function setMatchStatus($match_status)
    {
        $this->match_status = $match_status;
    }


    /**
     * @return HasMany
     */
    public function odds()
    {
        return $this->hasMany('App\Models\Odd');
    }

    /**
     * @param string $time
     * @param string $home
     * @param string $visitor
     * @param string $competition_name
     * @param string $competition_name_short
     * @param string $result
     * @param string $match_status
     *
     * @return static
     */
    public static function make($time, $home, $visitor, $competition_name, $competition_name_short, $result, $match_status)
    {
        return new static([
            'time'                   => $time,
            'home'                   => $home,
            'visitor'                => $visitor,
            'competition_name'       => $competition_name,
            'competition_name_short' => $competition_name_short,
            'result'                 => $result,
            'match_status'           => $match_status
        ]);
    }
}
