<?php namespace Mabasic\Kalista\Movies;

use Mabasic\Kalista\Core\Filesystem;

class MovieCollection {

    protected $filesystem;

    protected $movies = [];

    function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getMovies($source, $extensions, $exclusions)
    {
        $files = $this->filesystem->getFiles($source, $extensions, $exclusions);

        $this->movies = $this->mapFiles($files);
    }

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

    private function mapFiles($files)
    {

    }

}