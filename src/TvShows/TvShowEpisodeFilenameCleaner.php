<?php  namespace Mabasic\Kalista\TvShows;

use Mabasic\Kalista\Cleaners\FilenameCleaner;

class TvShowEpisodeFilenameCleaner extends FilenameCleaner {

    public function clean($filename)
    {
        return $this->prepare($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|X264|[0-9]/i");
    }

    public function getOnlySeasonAndEpisodeNumbers($filename, $regex)
    {
        $filename = $this->prepare($filename, $regex);

        $value = preg_replace('/[aA-zZ]| /i', '', $filename);

        return $value;
    }
}