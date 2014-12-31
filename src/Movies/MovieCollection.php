<?php namespace Mabasic\Kalista\Movies;

use Exception;
use Mabasic\Kalista\Collection;
use Mabasic\Kalista\Databases\Database;

class MovieCollection implements Collection {

    protected $movies = [];

    function __construct($movies)
    {
        $this->addMovies($movies);
    }

    public function add($movies)
    {
        if (is_array($movies))
        {
            return $this->addMovies($movies);
        }

        if ( ! $movies instanceof Movie)
            throw new Exception('Not a Movie!');

        $this->movies[] = $movies;

        return $this;
    }

    private function addMovies($movies)
    {
        foreach ($movies as $movie)
        {
            if ( ! $movie instanceof Movie)
                throw new Exception('Not a Movie!');

            $this->add($movie);
        }

        return $this;
    }

    public function fetchMovieNames(Database $database)
    {
        array_walk($this->movies, function(Movie $movie) use ($database)
        {
            $movie->setName($database->getName($movie));
        });

        return $this;
    }

    public function getCollection()
    {
        return $this->movies;
    }

    public function getHeaders()
    {
        return ['Filename', 'Movie Name'];
    }

    public function getRows()
    {
        $rows = [];

        foreach ($this->movies as $movie)
        {
            $rows[] = [
                $movie->file()->getFilename(),
                $movie->getName(),
            ];
        }

        return $rows;
    }

    public function remove($index)
    {
        // TODO: Implement remove() method.
    }
}