<?php namespace Mabasic\Kalista\Movies;

use Mabasic\Kalista\CollectionInterface;
use Mabasic\Kalista\Databases\DatabaseInterface;
use Mabasic\Kalista\Movies\Exceptions\MovieRequiredException;

class MovieCollection implements CollectionInterface {

    protected $movies = [];

    function __construct($movies = null)
    {
        if($movies !== null) $this->addMovies($movies);
    }

    public function add($movies)
    {
        if (is_array($movies))
        {
            return $this->addMovies($movies);
        }

        if ( ! $movies instanceof Movie)
            throw new MovieRequiredException('Not a Movie!');

        $this->movies[] = $movies;

        return $this;
    }

    private function addMovies($movies)
    {
        foreach ($movies as $movie)
        {
            if ( ! $movie instanceof Movie)
                throw new MovieRequiredException('Not a Movie!');

            $this->add($movie);
        }

        return $this;
    }

    public function fetchMovieNames(DatabaseInterface $database)
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

        array_walk($this->movies, function(Movie $movie) use (&$rows)
        {
            $rows[] = [
                $movie->file()->getFilename(),
                $movie->getName(),
            ];
        });

        return $rows;
    }

    public function remove($index)
    {
        unset($this->movies[$index]);
    }
}