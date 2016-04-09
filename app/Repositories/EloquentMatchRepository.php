<?php namespace App\Repositories;

use App\Models\Match;
use Illuminate\Support\Collection;

class EloquentMatchRepository implements MatchRepositoryInterface
{
    /**
     * @var Match
     */
    private $match;

    /**
     * EloquentMatchRepository constructor.
     *
     * @param Match $match
     */
    public function __construct(Match $match)
    {
        $this->match = $match;
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
}