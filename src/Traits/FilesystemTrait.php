<?php namespace Mabasic\Kalista\Traits;

trait FilesystemTrait {

    /**
     * @param $directoryPath
     */
    public function createDirectory($directoryPath)
    {
        if ( ! is_dir($directoryPath))
        {
            mkdir($directoryPath, 0777, true);
        }
    }

    /**
     * @param $source
     * @return array
     */
    public function scanDirectory($source)
    {
        return array_diff(scandir($source), ['..', '.', 'Thumbs.db']);
    }

}