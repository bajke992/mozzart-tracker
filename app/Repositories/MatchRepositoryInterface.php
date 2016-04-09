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
     * @param Match $match
     */
    public function save(Match $match);

    /**
     * @param Match $match
     */
    public function delete(Match $match);

}