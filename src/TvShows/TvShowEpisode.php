<?php namespace Mabasic\Kalista\TvShows;

use Mabasic\Kalista\Cleaners\CleanerInterface;
use Mabasic\Kalista\Cleaners\Exceptions\FilenameNotCleanedException;
use Mabasic\Kalista\Traits\SanitizerTrait;
use Mabasic\Kalista\VideoFileInterface;
use SplFileInfo;

class TvShowEpisode implements VideoFileInterface {

    use SanitizerTrait;

    protected $name;

    protected $file;

    protected $cleaner;

    protected $showName;

    public function __construct(SplFileInfo $file, CleanerInterface $cleaner)
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
        try
        {
            //dd($this->cleaner->clean($this->file->getFilename()));
            return $this->cleaner->clean($this->file->getFilename());
        }
        catch(FilenameNotCleanedException $exception)
        {
            $parts = explode(' - ', $this->file->getFilename());

            $parts[0] = preg_replace("/[']/i", '', $parts[0]);

            return strtolower($parts[0]);
        }
    }

    public function getCleanedFilenameWithNumbers()
    {
        try {
            $filename = $this->cleaner->prepare($this->file->getFilename(), "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|X264|2014|READNFO/i");

            //dd(preg_replace('/[aA-zZ]| /i', '', $filename));
            return preg_replace("/[aA-zZ]| |[-]|[']/i", '', $filename);
        }
        catch(FilenameNotCleanedException $exception)
        {
            $parts = explode(' - ', $this->file->getFilename());

            //$parts[0] = preg_replace("/[']/i", '', $parts[0]);
            $parts[1] = preg_replace('/[x]/i', '', $parts[1]);

            //dd($parts[0] . ' ' . $parts[1]);
            return $parts[1];
        }
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

    public function getOrganizedFilePathPartial()
    {
        return "\\{$this->showName}\\Season {$this->getSeason()}\\{$this->getFilename()}";
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

        // Return with leading zero (0) if necessary
        return sprintf('%02d', implode('', $episode));
    }

    public function setShowName($name)
    {
        $this->showName = $this->sanitizeText($name);

        return $this;
    }

    public function getShowName()
    {
        return $this->showName;
    }
}