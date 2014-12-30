<?php  namespace Mabasic\Kalista;

use SplFileInfo;

interface VideoFile {

    public function getName();

    public function setName($name);

    public function getCleanedFilename();

    public function getPathname();

    public function getFilename();

    /**
     * @return SplFileInfo
     */
    public function file();
}