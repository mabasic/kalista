<?php namespace Mabasic\Kalista\Services\TheMovieDB;

use Mabasic\Kalista\Providers\TheMovieDBServiceProvider;

class TheMovieDB {

    protected $theMovieDB;

    public function __construct(TheMovieDBServiceProvider $theMovieDBServiceProvider)
    {
        $this->theMovieDB = $theMovieDBServiceProvider->register();
    }
}