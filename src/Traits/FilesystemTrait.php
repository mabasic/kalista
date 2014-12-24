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

    /**
     * Returns an array of files from given items.
     *
     * @param $source
     * @param $items
     * @return array
     */
    private function filterFilesFromFolders($source, $items)
    {
        return array_filter($items, function ($item) use ($source)
        {
            return ! is_dir($source . '/' . $item);
        });
    }

}