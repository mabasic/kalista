<?php namespace Mabasic\Kalista\TvShows;

use Mabasic\Kalista\VideoFile;
use SplFileInfo;

class TvShowEpisode implements VideoFile {

    protected $name;

    protected $file;

    protected $cleaner;

    protected $showName;

    public function __construct(SplFileInfo $file, TvShowEpisodeFilenameCleaner $cleaner)
    {
        $this->file = $file;
        $this->cleaner = $cleaner;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCleanedFilename()
    {
        return $this->cleaner->clean($this->file->getFilename());
    }

    public function getCleanedFilenameWithNumbers()
    {
        return $this->cleaner->getOnlySeasonAndEpisodeNumbers($this->file->getFilename(), "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|X264/i");
    }

    public function file()
    {
        return $this->file;
    }

    public function getPathname()
    {
        return $this->file->getPath() . '\\' . $this->getFilename();
    }

    public function getFilename()
    {
        return $this->name . '.' . $this->file->getExtension();
    }

    public function getSeason()
    {
        $cleanedFilename = $this->getCleanedFilenameWithNumbers();

        $middle = floor(strlen($cleanedFilename) / 2);

        $season = array_slice(str_split($cleanedFilename), 0, $middle);

        return implode('', $season);
    }

    public function getEpisodeNumber()
    {
        $cleanedFilename = $this->getCleanedFilenameWithNumbers();

        $middle = floor(strlen($cleanedFilename) / 2);

        $episode = array_slice(str_split($cleanedFilename), $middle);

        return implode('', $episode);
    }

    public function setShowName($name)
    {
        $this->showName = $name;

        return $this;
    }

    public function getShowName()
    {
        return $this->showName;
    }
}