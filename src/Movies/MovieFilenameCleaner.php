<?php  namespace Mabasic\Kalista\Movies;

use Mabasic\Kalista\Cleaners\FilenameCleaner;

class MovieFilenameCleaner extends FilenameCleaner {

    public function clean($filename)
    {
        return $this->prepare($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|2014/i");
    }
}