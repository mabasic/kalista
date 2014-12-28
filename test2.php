<?php

use Mabasic\Kalista\Services\TheMovieDB\TheMovieDB;

require __DIR__ . '/vendor/autoload.php';


class Movie {

    protected $title = null;

    protected $file;

    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
    }


}

class MovieCollection {

    protected $movies = [];

    public function add($movies)
    {
        if (is_array($movies))
        {
            return $this->addMovies($movies);
        }

        $this->movies[] = $movies;

        return $this;
    }

    private function addMovies($movies)
    {
        foreach ($movies as $movie)
        {
            if ( ! $movie instanceof Movie)
                throw new Exception;

            $this->add($movie);
        }

        return $this;
    }

}