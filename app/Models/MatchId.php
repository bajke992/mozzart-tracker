<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchId extends Model
{
    protected $table = 'match_ids';

    protected $guarded = ['id'];

    public $timestamps = false;

    public function getId()
    {
        return $this->id;
    }

    public function getMatchId()
    {
        return $this->match_id;
    }

    public function setMatchId($match_id)
    {
        $this->match_id = $match_id;
    }

    public static function make($match_id)
    {
        return new static([
            'match_id' => $match_id
        ]);
    }
}
