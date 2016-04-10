<?php namespace App\Repositories;

use App\Models\Match;
use App\Models\MatchId;

class EloquentMatchIdRepository implements MatchIdRepositoryInterface
{
    /**
     * @var MatchId
     */
    private $matchId;

    /**
     * EloquentMatchIdRepository constructor.
     *
     * @param MatchId $matchId
     */
    public function __construct(MatchId $matchId)
    {
        $this->matchId = $matchId;
    }

    /**
     * @param integer $match_id
     *
     * @return Match
     */
    public function findByMatchId($match_id)
    {
        return $this->matchId->query()->where('match_id', $match_id)->first();
    }

    /**
     * @param MatchId $matchId
     */
    public function save(MatchId $matchId)
    {
        $matchId->save();
    }
}