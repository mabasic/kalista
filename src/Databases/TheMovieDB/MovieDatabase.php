<?php  namespace Mabasic\Kalista\Databases\TheMovieDB;

use Mabasic\Kalista\Databases\Exceptions\MovieNotFoundException;
use Mabasic\Kalista\VideoFile;
use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Repository\MovieRepository;

class MovieDatabase extends TheMovieDB {

    public function getName(VideoFile $videoFile)
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