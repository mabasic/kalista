<?php namespace Mabasic\Kalista\Core;

use Illuminate\Filesystem\Filesystem;
use stdClass;

class FilesystemHelper extends Filesystem {

    protected $filter;

    public function __construct(FileFilterer $filterer)
    {
        $this->filter = $filterer;
    }

    /**
     * It checks if the given directory exists and if
     * it does not exist it creates it. RECURSIVE
     *
     * @param $path
     */
    public function makeDirectoryOnlyIfItDoesNotExist($path)
    {
        if ( ! $this->exists($path))
        {
            $this->makeDirectory($path, 0755, true);
        }
    }

    /**
     * Returns all files with allowed extensions
     * from source folder filtered.
     *
     * @param $source
     * @return array
     */
    public function getAllowedAndFilteredFiles($source)
    {
        $files = $this->allFiles($source);

        $files = $this->filter->filterSampleFiles($files);

        return $this->filter->filterAllowedExtensions($files);
    }

    /**
     * Maps given files with given class.
     *
     * @param $files
     * @param $class
     * @return array
     */
    public function mapFiles($files, $class)
    {
        return array_map(function ($file) use ($class)
        {
            return new $class($file);
        }, $files);
    }

    public function getMappedAllowedAndFilteredFiles($source, $class)
    {
        $files = $this->getAllowedAndFilteredFiles($source);

        return $this->mapFiles($files, $class);
    }

}