<?php namespace Mabasic\Kalista\Mappers;

use Mabasic\Kalista\Cleaners\CleanerInterface;
use SplFileInfo;

interface MapperInterface {

    public function map(SplFileInfo $file, $class, CleanerInterface $cleaner);

    public function mapFiles($files, $class, CleanerInterface $cleaner);

}