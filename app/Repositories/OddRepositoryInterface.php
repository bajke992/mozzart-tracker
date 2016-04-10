<?php namespace App\Repositories;


use App\Models\Match;
use App\Models\Odd;
use Illuminate\Database\Eloquent\Collection;

interface OddRepositoryInterface
{

    /**
     * @return Collection|Odd[]
     */
    public function getAll();


    /**
     * @param Match $match
     *
     * @return Collection|Odd[]
     */
    public function getAllForMatch(Match $match);

    /**
     * @param Odd $odd
     */
    public function save(Odd $odd);

    /**
     * @param Match $match
     * @param Odd   $odd
     */
    public function saveOddToMatch(Match $match, Odd $odd);

    /**
     * @param Odd $odd
     */
    public function delete(Odd $odd);

}