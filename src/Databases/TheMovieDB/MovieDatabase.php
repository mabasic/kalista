<?php  namespace Mabasic\Kalista\Databases\TheMovieDB;

use Exception;
use Mabasic\Kalista\VideoFile;
use Tmdb\Model\Collection\ResultCollection;
use Tmdb\Repository\MovieRepository;

class MovieDatabase extends TheMovieDB {

    public function getName(VideoFile $videoFile)
    {
        return $this->searchMovieDatabaseByMovieName($videoFile->getCleanedFilename());
    }

    /**
     * TODO: This can be refactored!
     *
     * The function name does not make it clear on what it does.
     *
     * @param $name
     * @return mixed
     * @throws Exception
     */
    private function searchMovieDatabaseByMovieName($name)
    {
        $results = (new ResultCollection($this->theMovieDB->getSearchApi()->searchMovies($name)))->get('results');

        if (count($results) == 0)
            throw new Exception('Movie not found in database');

        $repository = new MovieRepository($this->theMovieDB);

        return $repository->load($results[0]['id'])->getTitle();
    }

}