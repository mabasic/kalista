<?php namespace Mabasic\Kalista\Databases\TheMovieDB;

use Mabasic\Kalista\Databases\Exceptions\MovieNotFoundException;
use Mabasic\Kalista\VideoFileInterface;
use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Repository\MovieRepository;
use Mabasic\Kalista\Databases\MovieDatabaseInterface;

class MovieDatabase extends TheMovieDB implements MovieDatabaseInterface {

    public function getName(VideoFileInterface $videoFile)
    {
        $results = $this->searchMovieDatabaseByFilename($videoFile->getCleanedFilename());

        $repository = new MovieRepository($this->theMovieDB);

        return $repository->load($results[0]['id'])->getTitle();
    }

    private function searchMovieDatabaseByFilename($name)
    {
        $results = (new ResultCollection($this->theMovieDB->getSearchApi()->searchMovies($name)))->get('results');

        if (count($results) == 0)
            throw new MovieNotFoundException("Movie: {$name} not found in database");

        return $results;
    }

}