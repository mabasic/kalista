<?php  namespace Mabasic\Kalista\Mappers;

use Exception;
use Mabasic\Kalista\Cleaners\Cleaner;
use SplFileInfo;

class FileMapper implements Mapper {

    public function map(SplFileInfo $file, $class, Cleaner $cleaner)
    {
        return new $class($file, $cleaner);
    }

    public function mapFiles($files, $class, Cleaner $cleaner)
    {
        return array_map(function ($file) use ($class, $cleaner)
        {
            if ( ! $file instanceof SplFileInfo)
                throw new Exception('Not a File!');

            return new $class($file, $cleaner);

        }, $files);
    }
}