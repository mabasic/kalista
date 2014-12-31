<?php namespace Mabasic\Kalista\TvShows;

use Exception;
use Mabasic\Kalista\Cleaners\Cleaner;
use Mabasic\Kalista\VideoFile;
use SplFileInfo;

class TvShowEpisode implements VideoFile {

    protected $name;

    protected $file;

    protected $cleaner;

    protected $showName;

    public function __construct(SplFileInfo $file, Cleaner $cleaner)
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
        $clean = $this->cleaner->clean($this->file->getFilename());

        if($clean == "")
            throw new Exception("{$this->file->getFilename()} cannot be cleaned.");

        return $this->cleaner->clean($this->file->getFilename());
    }

    public function getCleanedFilenameWithNumbers()
    {
        $filename = $this->cleaner->prepare($this->file->getFilename(), "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|X264/i");

        return preg_replace('/[aA-zZ]| /i', '', $filename);
    }

    public function file()
    {
        return $this->file;
    }

    public function getPathname()
    {
        //return $this->file->getPath() . '\\Season ' . $this->getSeason() . '\\' . $this->getFilename();
        return "{$this->file->getPath()}\\{$this->getFilename()}";
    }

    public function getFilename()
    {
        return "{$this->showName} - {$this->getSeason()}x{$this->getEpisodeNumber()} - {$this->name}.{$this->file->getExtension()}";
    }

    public function getSeason()
    {
        $cleanedFilename = $this->getCleanedFilenameWithNumbers();

        $middle = floor(strlen($cleanedFilename) / 2);

        $season = array_slice(str_split($cleanedFilename), 0, $middle);

        return (int) implode('', $season);
    }

    public function getEpisodeNumber()
    {
        $cleanedFilename = $this->getCleanedFilenameWithNumbers();

        $middle = floor(strlen($cleanedFilename) / 2);

        $episode = array_slice(str_split($cleanedFilename), $middle);

        return (int) implode('', $episode);
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