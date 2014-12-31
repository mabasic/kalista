<?php  namespace Mabasic\Kalista\Databases\TheMovieDB;

use Mabasic\Kalista\Databases\Exceptions\TvShowEpisodeNotFoundException;
use Mabasic\Kalista\VideoFile;
use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Repository\TvEpisodeRepository;
use Tmdb\Repository\TvRepository;

class TvShowEpisodeDatabase extends TheMovieDB {

    public function getName(VideoFile $videoFile)
    {
        $results = $this->searchTvShowEpisodeDatabaseByFilename($videoFile->getCleanedFilename());

        $repository = new TvEpisodeRepository($this->theMovieDB);

        return $repository->load($results[0]['id'], $videoFile->getSeason(), $videoFile->getEpisodeNumber())->getName();
    }

    public function getShowName(VideoFile $videoFile)
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