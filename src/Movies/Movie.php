<?php namespace Mabasic\Kalista\Movies;

use Mabasic\Kalista\Cleaners\CleanerInterface;
use Mabasic\Kalista\VideoFileInterface;
use SplFileInfo;

class Movie implements VideoFileInterface {

    protected $name;

    protected $file;

    protected $cleaner;

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
        return $this->cleaner->clean($this->file->getFilename());
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
}