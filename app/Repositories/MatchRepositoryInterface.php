<?php namespace App\Repositories;


use App\Models\Match;
use Illuminate\Support\Collection;

interface MatchRepositoryInterface
{

    /**
     * @return Collection|Match[]
     */
    public function getAll();

    /**
     * @param integer $match_id
     *
     * @return Collection|Match[]
     */
    public function findMatchByMatchId($match_id);

    /**
     * @param integer $match_id
     *
     * @return Match|bool
     */
    public function findMatchByMatchIdOrFail($match_id);

    /**
     * @param array $odds
     *
     * @return Match
     */
    public function matchOdds($odds);

    /**
     * @param Match $match
     */
    public function save(Match $match);

    /**
     * @param Match $match
     */
    public function delete(Match $match);

}