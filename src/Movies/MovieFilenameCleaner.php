<?php namespace Mabasic\Kalista\Movies;

use Mabasic\Kalista\Cleaners\FilenameCleaner;

class MovieFilenameCleaner extends FilenameCleaner {

    protected $extensions = 'MP4|AVI|MKV';

    protected $years = '2014|2009';

    protected $quality = 'HDTV|HC|HDRIP|1080P|X264|BLUERAY|AC3|XVID';

    protected $misc = 'YIFY';

    public function clean($filename)
    {
        $regex = "/{$this->extensions}|{$this->years}|{$this->quality}|{$this->misc}/i";

        return $this->prepare($filename, $regex);
    }
}