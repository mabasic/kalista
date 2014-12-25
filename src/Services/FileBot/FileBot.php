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
            $this->renameMovie($file);
        }
    }

    public function renameTVShow(SplFileInfo $tvShow, $database = 'TheTVDB', $format = '{n} - {sxe} - {t}')
    {
        exec("filebot --format " . '"' . $format . '"' . " -rename " . '"' . $tvShow->getPathname() . '"' . " --db {$database} -non-strict");
    }

    public function renameTVShows($files)
    {
        foreach($files as $file)
        {
            $this->renameTVShow($file);
        }
    }
}