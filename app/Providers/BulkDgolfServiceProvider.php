<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BulkDgolfServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind( // PART: Admin, Module Admin
            'App\Services\Interfaces\UserInterface',
            'App\Services\Repositories\UserRepository'
        );

        $this->app->bind( // PART: Module Masters
            'Modules\Masters\App\Services\Interfaces\MastersInterface',
            'Modules\Masters\App\Services\Repositories\MastersRepository'
        );

        $this->app->bind( // PART: Module Community
            'Modules\Community\App\Services\Interfaces\CommunityInterface',
            'Modules\Community\App\Services\Repositories\CommunityRepository'
        );

        $this->app->bind( // PART: Module ScoreHandicap
            'Modules\ScoreHandicap\App\Services\Interfaces\ScoreHandicapInterface',
            'Modules\ScoreHandicap\App\Services\Repositories\ScoreHandicapRepository'
        );

        $this->app->bind( // PART: Module MyGames
            'Modules\MyGames\App\Services\Interfaces\MyGamesInterface',
            'Modules\MyGames\App\Services\Repositories\MyGamesRepository'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
