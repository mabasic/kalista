<?php  namespace Mabasic\Kalista\Services\TheMovieDB;

use Tmdb\Repository\MovieRepository;

class Movies extends TheMovieDB {

    public function searchMoviesByTitle($title)
    {
        return $this->theMovieDB->getSearchApi()->searchMovies($title);
    }

    public function getMovieRepositoryByTitle($title)
    {
        $results = $this->searchMoviesByTitle($title);

        // Get the first result.
        $movie = $results['results'][0];

        $repository = new MovieRepository($this->theMovieDB);

        return $repository->load($movie['id']);
    }

    public function getMovieTitle($title)
    {
        return $this->getMovieRepositoryByTitle($title)->getTitle();
    }

}