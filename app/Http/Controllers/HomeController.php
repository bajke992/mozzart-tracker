<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repositories\MatchIdRepositoryInterface;
use App\Repositories\MatchRepositoryInterface;
use App\Repositories\OddRepositoryInterface;
use App\Services\MatchHandler;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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
     * @param MatchRepositoryInterface $matchRepo
     * @param OddRepositoryInterface $oddRepo
     * @param MatchIdRepositoryInterface $matchIdRepo
     */
    public function __construct(MatchRepositoryInterface $matchRepo, OddRepositoryInterface $oddRepo, MatchIdRepositoryInterface $matchIdRepo)
    {
        $this->matchRepo = $matchRepo;
        $this->oddRepo = $oddRepo;
        $this->matchIdRepo = $matchIdRepo;
    }

    public function index(MatchHandler $matchHandler)
    {
        $http = new Client();
        $lastDate = null;
        $currentPage = null;
        $numberOfPages = null;

        if(Session::has('lastDate')) {
            $lastDate = Session::get('lastDate');
            if($lastDate !== date('Y-m-d')) {
                Session::forget('lastDate');
                Session::forget('lastPage');
                Session::forget('numberOfPages');
                Session::put('lastDate', date('Y-m-d'));
                $lastDate = Session::get('lastDate');
            }
        } else {
            $lastDate = date('Y-m-d');

            Session::forget('lastDate');
            Session::forget('lastPage');
            Session::forget('numberOfPages');

            Session::put('lastDate', $lastDate);
        }

        if(Session::has('lastPage')) {
            $currentPage = Session::get('lastPage');
        } else {
            $currentPage = 1;
        }

        Session::put('lastPage', $currentPage);

        if(Session::has('numberOfPages')) {
            $numberOfPages = Session::get('numberOfPages');
        } else {
            $numberOfPages = 0;
        }

        $settings = [
            'sports' => [1],
            'dateRange' => [
                'from' => strtotime(date('d-m-Y 00:00:00')) . '000',
                'to' => null
            ],
            'matchStatus' => [
                'FT',
            ],
            "matchSorting" => "BY_TIME",
            "selectedCompetitions" => [],
            "selectedGames" => null,
            "languageId" => 1,
            "matchNumber" => null,
            "favouriteMatchNumbers" => [],
            "pageNumber" => $currentPage
        ];

        /*$response = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => $settings
        ]);

        $responseBody = json_decode($response->getBody());

        if($numberOfPages !== $responseBody->paginationInfo->numberOfTotalPages) {
            $numberOfPages = $responseBody->paginationInfo->numberOfTotalPages;
            Session::put('numberOfPages', $numberOfPages);
        }

        $matchHandler->handle($responseBody->matches, $responseBody->gamesBySport->{1}, $this->matchRepo, $this->oddRepo, $this->matchIdRepo);

        if($currentPage !== $numberOfPages) {
            for($page = $currentPage + 1; $page <= $numberOfPages; $page++) {
                $currentPage = $page;
                $settings['pageNumber'] = $page;
                Session::put('lastPage', $currentPage);

                $response = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json'
                    ],
                    'json' => $settings
                ]);

                $responseBody = json_decode($response->getBody());
                $matchHandler->handle($responseBody->matches, $responseBody->gamesBySport->{1}, $this->matchRepo, $this->oddRepo, $this->matchIdRepo);
            }
        }

        Session::put('lastDate', date('Y-m-d'));
        */

        $results = $this->matchRepo->getAll();

        return view('home', [
            'results' => $results
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOld(MatchHandler $matchHandler)
    {
        $http = new Client();
        $pageNumber = session()->get('pageNumber');
//        $pages = session()->get('pages');
        $pages = null;

        $settings = [
            'sports' => [1],
            'dateRange' => [
                'from' => strtotime(date('d-m-Y 00:00:00')) . '000',
                'to' => null
            ],
            'matchStatus' => [
                'FT',
            ],
            "matchSorting" => "BY_TIME",
            "selectedCompetitions" => [],
            "selectedGames" => null,
            "languageId" => 1,
            "matchNumber" => null,
            "favouriteMatchNumbers" => [],
            "pageNumber" => $pageNumber
        ];

        $response = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => $settings
        ]);

        $responseBody = json_decode($response->getBody());

        if (count($responseBody->matches) !== 0) {

            session()->put('pages', $responseBody->paginationInfo->numberOfTotalPages);
            $pages = session()->get('pages');
            session()->put('pageNumber', $responseBody->paginationInfo->currentPage);

//            $pages = $responseBody->paginationInfo->numberOfTotalPages;

//            $matchHandler->handle($responseBody->matches, $responseBody->gamesBySport->{1}, $this->matchRepo, $this->oddRepo, $this->matchIdRepo);
            echo 'session: '.session()->get('pageNumber').'<br>';
            echo 'request: '.$responseBody->paginationInfo->currentPage.'<br>';

//            for ($i = $responseBody->paginationInfo->currentPage + 1; $i <= $pages; $i++) {
            for ($i = $pageNumber + 1; $i <= $pages; $i++) {
                $settings['pageNumber'] = $i;
                $res = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json'
                    ],
                    'json' => $settings
                ]);

                $body = json_decode($res->getBody());
                session()->put('pageNumber', $body->paginationInfo->currentPage);
                echo 'session: '.session()->get('pageNumber').'<br>';
                echo 'request: '.$body->paginationInfo->currentPage.'<br>';
//                $matchHandler->handle($body->matches, $body->gamesBySport->{1}, $this->matchRepo, $this->oddRepo, $this->matchIdRepo);
            }
        }

        echo "session - pages: ".session()->get('pages').'<br>';
        echo "request - pages: ".$responseBody->paginationInfo->numberOfTotalPages;

        die;

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

        $http = new Client();
        $settings = [
            'sports' => [1],
            'dateRange' => [
                'from' => strtotime(date('d-m-Y 00:00:00')) . '000',
                'to' => null
            ],
            'matchStatus' => [
                'READY',
            ],
            "matchSorting" => "BY_TIME",
            "selectedCompetitions" => [],
            "selectedGames" => null,
            "languageId" => 1,
            "matchNumber" => null,
            "favouriteMatchNumbers" => [],
            "pageNumber" => 1
        ];

        $response = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => $settings
        ]);

        $responseBody = json_decode($response->getBody());

        $data[] = json_decode($input['data']);

        $matchHandler->handle($data, $responseBody->gamesBySport->{1}, $this->matchRepo, $this->oddRepo, $this->matchIdRepo);
    }

    public function offerRequest(Request $request)
    {

        $matchNumber = null;
        $pageNumber = null;

        if ($request->has('matchNumber')) $matchNumber = $request->get('matchNumber');
        if ($request->has('pageNumber')) $pageNumber = $request->get('pageNumber');


        $http = new Client();
        $settings = [
            'sports' => [1],
            'dateRange' => [
                'from' => null,
                'to' => null
            ],
            'matchStatus' => [
                'LIVE',
            ],
            "matchSorting" => "BY_TIME",
            "selectedCompetitions" => [],
            "selectedGames" => null,
            "languageId" => 1,
            "matchNumber" => $matchNumber,
            "favouriteMatchNumbers" => [],
            "pageNumber" => $pageNumber
        ];

        $response = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => $settings
        ]);

        return $response->getBody();
    }

    public function readyRequest(Request $request)
    {

        $matchNumber = null;
        $pageNumber = null;

        if ($request->has('matchNumber')) $matchNumber = $request->get('matchNumber');
        if ($request->has('pageNumber')) $pageNumber = $request->get('pageNumber');

        $http = new Client();
        $settings = [
            'sports' => [1],
            'dateRange' => [
                'from' => null,
                'to' => null
            ],
            'matchStatus' => [
                'READY',
            ],
            "matchSorting" => "BY_TIME",
            "selectedCompetitions" => [],
            "selectedGames" => null,
            "languageId" => 1,
            "matchNumber" => $matchNumber,
            "favouriteMatchNumbers" => [],
            "pageNumber" => $pageNumber
        ];

        $response = $http->request('POST', 'https://www.mozzartbet.com/MozzartWS/oddsLive/offer', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'json' => $settings
        ]);

        return $response->getBody();
    }

    public function live()
    {
        return view('live');
    }

    public function ready()
    {
        return view('ready');
    }
}
