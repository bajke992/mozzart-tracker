<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repositories\MatchIdRepositoryInterface;
use App\Repositories\MatchRepositoryInterface;
use App\Repositories\OddRepositoryInterface;
use App\Services\MatchHandler;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var MatchRepositoryInterface
     */
    private $matchRepo;
    /**
     * @var OddRepositoryInterface
     */
    private $oddRepo;
    /**
     * @var MatchIdRepositoryInterface
     */
    private $matchIdRepo;

    /**
     * Create a new controller instance.
     *
     * @param MatchRepositoryInterface   $matchRepo
     * @param OddRepositoryInterface     $oddRepo
     * @param MatchIdRepositoryInterface $matchIdRepo
     */
    public function __construct(MatchRepositoryInterface $matchRepo, OddRepositoryInterface $oddRepo, MatchIdRepositoryInterface $matchIdRepo)
    {
        $this->matchRepo   = $matchRepo;
        $this->oddRepo     = $oddRepo;
        $this->matchIdRepo = $matchIdRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MatchHandler $matchHandler)
    {
        $http     = new Client();
        $settings = [
            'sports'                => [1],
            'dateRange'             => [
                'from' => strtotime(date('d-m-Y 00:00:00')).'000',
                'to'   => null
            ],
            'matchStatus'           => [
                'FT',
            ],
            "matchSorting"          => "BY_TIME",
            "selectedCompetitions"  => [],
            "selectedGames"         => null,
            "languageId"            => 1,
            "matchNumber"           => null,
            "favouriteMatchNumbers" => [],
            "pageNumber"            => 1
        ];

        $response = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json'    => $settings
        ]);

        $responseBody = json_decode($response->getBody());

        if(count($responseBody->matches) !== 0) {

            $pages = $responseBody->paginationInfo->numberOfTotalPages;

            $matchHandler->handle($responseBody->matches, $responseBody->gamesBySport->{1}, $this->matchRepo, $this->oddRepo, $this->matchIdRepo);

            for ($i = $responseBody->paginationInfo->currentPage + 1; $i <= $pages; $i++) {
                $settings['pageNumber'] = $i;
                $res                    = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
                    'headers' => [
                        'Accept'       => 'application/json',
                        'Content-Type' => 'application/json'
                    ],
                    'json'    => $settings
                ]);

                $body = json_decode($res->getBody());
                $matchHandler->handle($body->matches, $body->gamesBySport->{1}, $this->matchRepo, $this->oddRepo, $this->matchIdRepo);
            }
        }

        $results = $this->matchRepo->getAll();

        return view('home', [
            'results' => $results
        ]);
    }

    public function postFinished(Request $request, MatchHandler $matchHandler)
    {
        $input = $request->only([
            'data'
        ]);

        $http     = new Client();
        $settings = [
            'sports'                => [1],
            'dateRange'             => [
                'from' => strtotime(date('d-m-Y 00:00:00')).'000',
                'to'   => null
            ],
            'matchStatus'           => [
                'READY',
            ],
            "matchSorting"          => "BY_TIME",
            "selectedCompetitions"  => [],
            "selectedGames"         => null,
            "languageId"            => 1,
            "matchNumber"           => null,
            "favouriteMatchNumbers" => [],
            "pageNumber"            => 1
        ];

        $response = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json'    => $settings
        ]);

        $responseBody = json_decode($response->getBody());

        $data[] = json_decode($input['data']);

        $matchHandler->handle($data, $responseBody->gamesBySport->{1}, $this->matchRepo, $this->oddRepo, $this->matchIdRepo);
    }

    public function performRequest(Request $request)
    {

        $matchNumber = null;
        $pageNumber = null;

        if($request->has('matchNumber')) $matchNumber = $request->get('matchNumber');
        if($request->has('pageNumber')) $pageNumber = $request->get('pageNumber');


        $http     = new Client();
        $settings = [
            'sports'                => [1],
            'dateRange'             => [
                'from' => null,
                'to'   => null
            ],
            'matchStatus'           => [
                'LIVE',
            ],
            "matchSorting"          => "BY_TIME",
            "selectedCompetitions"  => [],
            "selectedGames"         => null,
            "languageId"            => 1,
            "matchNumber"           => $matchNumber,
            "favouriteMatchNumbers" => [],
            "pageNumber"            => $pageNumber
        ];

        $response = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json'    => $settings
        ]);

        return $response->getBody();
    }

    public function live()
    {
        return view('live');
    }

    public function ready()
    {
        return 'ready';
    }
}
