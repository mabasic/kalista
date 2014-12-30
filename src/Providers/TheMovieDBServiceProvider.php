<?php namespace Mabasic\Kalista\Providers;

use Mabasic\Kalista\Services\Environmental\Environmental;
use Tmdb\ApiToken;
use Tmdb\Client;

class TheMovieDBServiceProvider implements ServiceProvider {

    protected $environmental;

    public function __construct(Environmental $environmental)
    {
        $this->environmental = $environmental;
    }

    public function register()
    {
        $tmdb_api_key = $this->environmental->getEnvironmentVariable('tmdb_api_key');

        $token = new ApiToken($tmdb_api_key);

        return new Client($token);
    }

}