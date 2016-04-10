<?php namespace App\Repositories;

use App\Models\Match;
use App\Models\Odd;
use Illuminate\Database\Eloquent\Collection;

class EloquentOddRepository implements OddRepositoryInterface
{
    /**
     * @var Odd
     */
    private $odd;

    /**
     * EloquentOddRepository constructor.
     *
     * @param Odd $odd
     */
    public function __construct(Odd $odd)
    {
        $this->odd = $odd;
    }

    /**
     * @return Collection|Odd[]
     */
    public function getAll()
    {
        return $this->odd->all();
    }

    /**
     * @param Match $match
     *
     * @return Collection|Odd[]
     */
    public function getAllForMatch(Match $match)
    {
        return $match->odds;
    }

    /**
     * @param Odd $odd
     */
    public function save(Odd $odd)
    {
        $odd->save();
    }

    /**
     * @param Match $match
     * @param Odd   $odd
     */
    public function saveOddToMatch(Match $match, Odd $odd)
    {
        $match->odds()->save($odd);
    }

    /**
     * @param Odd $odd
     */
    public function delete(Odd $odd)
    {
        $odd->delete();
    }
}