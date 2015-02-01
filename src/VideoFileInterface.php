<?php namespace Mabasic\Kalista;

use SplFileInfo;

interface VideoFileInterface {

    public function getName();

    public function setName($name);

    public function getCleanedFilename();

    public function getPathname();

    public function getFilename();

    public function getOrganizedFilePathPartial();

    /**
     * @return SplFileInfo
     */
    public function file();
}