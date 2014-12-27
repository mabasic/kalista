<?php  namespace Mabasic\Kalista\Services\TheMovieDB;

use Tmdb\Repository\TvEpisodeRepository;

class TvShows extends TheMovieDB {

    public function searchTvShowsByTitle($title)
    {
        return $this->theMovieDB->getSearchApi()->searchTv($title);
    }

    public function getTvShowRepositoryByTitle($title)
    {
        $results = $this->searchTvShowsByTitle($title);

        // Get the first result.
        $tvShow = $results['results'][0];

        $repository = new TvEpisodeRepository($this->theMovieDB);

        return $repository->load($tvShow['id'], 1, 1);
    }

    public function getTvShowName($title)
    {
        return $this->getTvShowRepositoryByTitle($title)->getName();
    }

}