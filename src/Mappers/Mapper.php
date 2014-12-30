<?php  namespace Mabasic\Kalista\Mappers;

use Mabasic\Kalista\Cleaners\Cleaner;
use SplFileInfo;

interface Mapper {

    public function map(SplFileInfo $file, $class, Cleaner $cleaner);

    public function mapFiles($files, $class, Cleaner $cleaner);

}