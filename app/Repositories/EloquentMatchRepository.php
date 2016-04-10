<?php namespace App\Repositories;

use App\Models\Match;
use App\Models\Odd;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Support\Collection;

class EloquentMatchRepository implements MatchRepositoryInterface
{
    /**
     * @var Match
     */
    private $match;
    /**
     * @var Odd
     */
    private $odd;

    /**
     * EloquentMatchRepository constructor.
     *
     * @param Match $match
     * @param Odd   $odd
     */
    public function __construct(Match $match, Odd $odd)
    {
        $this->match = $match;
        $this->odd = $odd;
    }

    /**
     * @return Collection|Match[]
     */
    public function getAll()
    {
        return $this->match->all();
    }

    /**
     * @param Match $match
     */
    public function save(Match $match)
    {
        $match->save();
    }

    /**
     * @param Match $match
     */
    public function delete(Match $match)
    {
        $match->delete();
    }

    /**
     * @param integer $match_id
     *
     * @return Collection|Match[]
     */
    public function findMatchByMatchId($match_id)
    {
        return $this->match->query()->where('match_id', $match_id)->get();
    }

    /**
     * @param integer $match_id
     *
     * @return Match|bool
     */
    public function findMatchByMatchIdOrFail($match_id)
    {
        $matches = $this->findMatchByMatchId($match_id);
        if($matches->count() === 0) {
            return false;
        }

        return $matches;
    }

    /**
     * @param array $odds
     *
     * @return Match
     */
    public function matchOdds($odds){
        $query = $this->odd->query();


        foreach($odds as $odd){
            $query->where('name', $odd['name']);
            $query->where('value', $odd['value']);
        }

        return $query->first();
    }

}