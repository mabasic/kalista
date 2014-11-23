<?php namespace Mabasic\Kalista;

class File {

    protected $filename;
    protected $path;

    public function __construct($name, $path)
    {
        $this->filename = $name;
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param mixed $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getFullPath()
    {
        return $this->path . '/' . $this->filename;
    }

    public function getExtension()
    {
        return substr($this->filename, strrpos($this->filename, '.') + 1);
    }

}