<?php

namespace App\Providers;

use App\Models\Match;
use App\Models\MatchId;
use App\Models\Odd;
use App\Repositories\EloquentMatchIdRepository;
use App\Repositories\EloquentMatchRepository;
use App\Repositories\EloquentOddRepository;
use App\Repositories\MatchIdRepositoryInterface;
use App\Repositories\MatchRepositoryInterface;
use App\Repositories\OddRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(MatchRepositoryInterface::class, function(Application $app) {
            return new EloquentMatchRepository(new Match(), new Odd());
        });
        $this->app->bind(OddRepositoryInterface::class, function(Application $app) {
            return new EloquentOddRepository(new Odd());
        });
        $this->app->bind(MatchIdRepositoryInterface::class, function(Application $app) {
            return new EloquentMatchIdRepository(new MatchId());
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
