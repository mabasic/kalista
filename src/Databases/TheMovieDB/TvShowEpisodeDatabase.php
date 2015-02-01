<?php namespace Mabasic\Kalista\Databases\TheMovieDB;

use Mabasic\Kalista\Databases\Exceptions\TvShowEpisodeNotFoundException;
use Mabasic\Kalista\VideoFileInterface;
use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Repository\TvEpisodeRepository;
use Tmdb\Repository\TvRepository;
use Mabasic\Kalista\Databases\TvShowEpisodeDatabaseInterface;;

class TvShowEpisodeDatabase extends TheMovieDB implements TvShowEpisodeDatabaseInterface {

    public function getName(VideoFileInterface $videoFile)
    {
        $results = $this->searchTvShowEpisodeDatabaseByFilename($videoFile->getCleanedFilename());

        $repository = new TvEpisodeRepository($this->theMovieDB);

        return $repository->load($results[0]['id'], $videoFile->getSeason(), $videoFile->getEpisodeNumber())->getName();
    }

    public function getShowName(VideoFileInterface $videoFile)
    {
        $results = $this->searchTvShowEpisodeDatabaseByFilename($videoFile->getCleanedFilename());

        $repository = new TvRepository($this->theMovieDB);

        return $repository->load($results[0]['id'])->getName();
    }

    private function searchTvShowEpisodeDatabaseByFilename($name)
    {
        $results = (new ResultCollection($this->theMovieDB->getSearchApi()->searchTv($name)))->get('results');

        if (count($results) == 0)
            throw new TvShowEpisodeNotFoundException("TvShowEpisode: {$name} not found in database");

        return $results;
    }

}