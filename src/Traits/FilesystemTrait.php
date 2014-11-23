<?php namespace Mabasic\Kalista\Traits;

trait FilesystemTrait {

    /**
     * @param $directoryPath
     */
    public function createDirectory($directoryPath)
    {
        if ( ! is_dir($directoryPath))
        {
            mkdir($directoryPath);
        }
    }

    /**
     * @param $source
     * @return array
     */
    public function scanDirectory($source)
    {
        return array_diff(scandir($source), ['..', '.']);
    }

}