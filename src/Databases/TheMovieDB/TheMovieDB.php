<?php namespace Mabasic\Kalista\Databases\TheMovieDB;

use Mabasic\Kalista\Databases\Database;
use Mabasic\Kalista\Providers\TheMovieDBServiceProvider;

abstract class TheMovieDB implements Database {

    protected $theMovieDB;

    public function __construct(TheMovieDBServiceProvider $theMovieDBServiceProvider)
    {
        $this->theMovieDB = $theMovieDBServiceProvider->register();
    }

}