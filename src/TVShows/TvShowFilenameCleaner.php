<?php  namespace Mabasic\Kalista\TVShows;

use Mabasic\Kalista\Core\FilenameCleaner;

class TvShowFilenameCleaner extends FilenameCleaner {


    /**
     * Cleans given tv show filename with provided regex.
     *
     * @param $filename
     * @return string
     */
    public function cleanTvShowFilename($filename)
    {
        return $this->cleanFilename($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|[0-9]/i");
    }

}

