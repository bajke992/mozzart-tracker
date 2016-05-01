<?php namespace App\Services;

use App\Models\Match;
use App\Models\MatchId;
use App\Models\Odd;
use App\Repositories\MatchIdRepositoryInterface;
use App\Repositories\MatchRepositoryInterface;
use App\Repositories\OddRepositoryInterface;

class MatchHandler
{

    /**
     * @param array                      $matches
     * @param array                      $categories
     * @param MatchRepositoryInterface   $matchRepo
     * @param OddRepositoryInterface     $oddRepo
     * @param MatchIdRepositoryInterface $matchIdRepo
     */
    public function handle(array $matches, array $categories, MatchRepositoryInterface $matchRepo, OddRepositoryInterface $oddRepo, MatchIdRepositoryInterface $matchIdRepo)
    {
        foreach ($matches as $match) {
            if ($matchIdRepo->findByMatchId($match->matchId) || !Odd::hasAllOdds($match->odds)) {
                continue;
            }

            $odds = $this->getOdds($match, $categories);

//            dd(array_slice($odds, 0, 6));

            /** @var Match $sample */
            $sample = $matchRepo->matchOdds(array_slice($odds, 0, 6));
//            var_dump($odds);
//            var_dump($sample); die();
            if ($sample !== null) {
//                dd($sample);
                $sample->incrementCount();
                $matchRepo->save($sample);
                $this->incrementWinOdds($odds, $sample);
            } else {
                $sample = Match::make();
                $matchRepo->save($sample);
                $this->makeOdds($sample, $odds, $oddRepo);
                $this->incrementWinOdds($odds, $sample);
            }

            $matchId = MatchId::make($match->matchId);
            $matchIdRepo->save($matchId);
        }
    }

    /**
     * @param $match
     * @param $categories
     *
     * @return array
     */
    private function getOdds($match, $categories)
    {
        $result = [];
        foreach ($match->odds as $odds) {
            if (Odd::checkOdd($odds->id)) {
                foreach ($odds->subgames as $subgame) {
                    if (Odd::checkSubGame($odds->id, $subgame->id)) {
                        $result[] = [
                            'name'      => Odd::$ODD_NAMES[$odds->id][$subgame->id],
                            'category'  => Odd::getNameByCategory($categories, $odds->id - 1),
                            'value'     => property_exists($subgame, 'value') ? $subgame->value : null,
                            'winStatus' => property_exists($subgame, 'winStatus') ? $subgame->winStatus : null
                        ];
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param       $odds
     * @param Match $match
     */
    private function incrementWinOdds($odds, Match $match)
    {
//        $tmp_odds = $match->odds->slice(0, 6);
        $tmp_odds = $match->odds;

        foreach ($tmp_odds as $k => $tmp_odd) {
            if (array_key_exists($k, $odds) && $odds[$k]['winStatus'] === "WIN") {
                $tmp_odd->incrementWinCount();
                $tmp_odd->save();
            }
        }
    }

    /**
     * @param Match                  $match
     * @param                        $odds
     * @param OddRepositoryInterface $oddRepo
     */
    private function makeOdds(Match $match, $odds, OddRepositoryInterface $oddRepo)
    {
        foreach ($odds as $odd) {
            $odd_tmp = Odd::make($odd['name'], $odd['category'], $odd['value']);
            $oddRepo->saveOddToMatch($match, $odd_tmp);
        }
    }
}