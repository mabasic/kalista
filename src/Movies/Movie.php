<?php namespace Mabasic\Kalista\Movies;

use Symfony\Component\Finder\SplFileInfo;

class Movie {

    public $file;

    public $cleaned = false;

    public $title;

    public $renamed;

    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * @param mixed $title
     * @return $this
     */
    public function setTitle($title)
    {
        if ($title === false)
        {
            $this->title = null;
        }
        else
        {
            $this->title = $title;
            $this->cleaned = true;
        }

        return $this;
    }

    public function getModifiedFilename()
    {
        return $this->title . '.' . $this->file->getExtension();
    }

    public function getModifiedPath()
    {
        return $this->file->getPath() . '\\' . $this->getModifiedFilename();
    }

    public function getTitle()
    {
        return $this->title;
    }
}