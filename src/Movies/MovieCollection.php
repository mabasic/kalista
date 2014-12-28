<?php namespace Mabasic\Kalista\Movies;

class MovieCollection {

    protected $movies = [];

    public function getRenamedMoviesOnly()
    {
        return array_filter($this->movies, function (Movie $movie)
        {
            return $movie->renamed;
        });
    }

    public function addMovie(Movie $movie)
    {
        $this->movies[] = $movie;
    }

    public function getTableHeaders()
    {
        return ['Old', 'New', 'Cleaned', 'Renamed'];
    }

    public function getTableRows()
    {
        $rows = [];

        foreach ($this->movies as $movie)
        {
            $rows[] = [
                $movie->file->getFilename(),
                $movie->title,
                $movie->cleaned,
                $movie->renamed
            ];
        }

        return $rows;
    }

}