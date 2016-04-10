<?php namespace App\Repositories;


use App\Models\Match;
use App\Models\MatchId;

interface MatchIdRepositoryInterface
{
    /**
     * @param integer $match_id
     *
     * @return Match
     */
    public function findByMatchId($match_id);

    /**
     * @param MatchId $matchId
     */
    public function save(MatchId $matchId);
}