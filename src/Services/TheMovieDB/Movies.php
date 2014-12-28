<?php namespace Mabasic\Kalista\Services\TheMovieDB;

use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Repository\MovieRepository;

class Movies extends TheMovieDB {

    public function searchMoviesByTitle($title)
    {
        $result = $this->theMovieDB->getSearchApi()->searchMovies($title);

        return new ResultCollection($result);
    }

    public function getMovieRepositoryByTitle($title)
    {
        $results = $this->searchMoviesByTitle($title)->get('results');

        if (count($results) == 0) return false;

        $repository = new MovieRepository($this->theMovieDB);

        return $repository->load($results[0]['id']);
    }

    public function getMovieTitle($title)
    {
        $output = $this->getMovieRepositoryByTitle($title);

        if ($output == false) return false;

        return $output->getTitle();
    }

}