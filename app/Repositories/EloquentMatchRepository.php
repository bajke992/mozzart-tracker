<?php namespace App\Repositories;

use App\Models\Match;
use App\Models\Odd;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

        $matches = $this->getAll();
        $result = null;
        $oddsss = [];

        foreach($odds as $odd) {
            $oddsss[] = [
                'name' => $odd['name'],
                'category' => $odd['category'],
                'value' => $odd['value']
            ];
        }

        foreach($matches as $match){
            $oddss = [];
            foreach(array_slice($match->odds->toArray(), 0, 6) as $tmp_odd) {
                $oddss[] = [
                    'name' => $tmp_odd['name'],
                    'category' => $tmp_odd['category'],
                    'value' => $tmp_odd['value']
                ];
            }
            if($oddsss === $oddss) return $match;
        }
    }

}