<?php  namespace Mabasic\Kalista\Services\TheMovieDB;

use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Repository\TvEpisodeRepository;

class TvShows extends TheMovieDB {

    public function searchTvShowsByTitle($title)
    {
        $result = $this->theMovieDB->getSearchApi()->searchTv($title);

        return new ResultCollection($result);
    }

    public function getTvShowRepositoryByTitle($title)
    {
        $results = $this->searchTvShowsByTitle($title);

        $results = $results->get('results');

        if (count($results) == 0) return false;

        $repository = new TvEpisodeRepository($this->theMovieDB);

        return $repository->load($results[0]['id'], 1, 1);
    }

    public function getTvShowName($title)
    {
        $output = $this->getTvShowRepositoryByTitle($title);

        if ($output == false) return false;

        return $output->getName();
    }

}