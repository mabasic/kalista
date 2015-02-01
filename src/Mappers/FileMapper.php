<?php namespace Mabasic\Kalista\Mappers;

use Mabasic\Kalista\Cleaners\CleanerInterface;
use Mabasic\Kalista\Mappers\Exceptions\FileRequiredException;
use SplFileInfo;

class FileMapper implements MapperInterface {

    public function map(SplFileInfo $file, $class, CleanerInterface $cleaner)
    {
        return new $class($file, $cleaner);
    }

    public function mapFiles($files, $class, CleanerInterface $cleaner)
    {
        return array_map(function ($file) use ($class, $cleaner)
        {
            if ( ! $file instanceof SplFileInfo)
                throw new FileRequiredException('Not a File!');

            return new $class($file, $cleaner);

        }, $files);
    }
}