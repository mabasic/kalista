<?php namespace Mabasic\Kalista\Services\FileBot;

use Symfony\Component\Finder\SplFileInfo;

class FileBot {

    public function renameMovie(SplFileInfo $movie, $database = 'TheMovieDB', $format = '{n} {[y, certification, rating]}')
    {
        exec("filebot --format " . '"' . $format . '"' . " -rename " . '"' . $movie->getPathname() . '"' . " --db {$database} -non-strict");
    }

    public function renameMovies($files)
    {
        foreach($files as $file)
        {
            //var_dump($file);
            $this->renameMovie($file);
        }
    }
}