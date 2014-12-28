<?php namespace Mabasic\Kalista\Movies;

use Mabasic\Kalista\Core\FilenameCleaner;

class MovieFilenameCleaner extends FilenameCleaner {


    /**
     * Cleans given movie filename with provided regex.
     *
     * @param $filename
     * @return string
     */
    public function cleanMovieFilename($filename)
    {
        return $this->cleanFilename($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|2014/i");
    }

}