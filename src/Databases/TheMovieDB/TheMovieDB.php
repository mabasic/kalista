<?php namespace Mabasic\Kalista\Databases\TheMovieDB;

use Mabasic\Kalista\Databases\DatabaseInterface;
use Mabasic\Kalista\Providers\TheMovieDBServiceProvider;

abstract class TheMovieDB implements DatabaseInterface {

    protected $theMovieDB;

    public function __construct(TheMovieDBServiceProvider $theMovieDBServiceProvider)
    {
        $this->theMovieDB = $theMovieDBServiceProvider->register();
    }

}