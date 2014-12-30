<?php namespace Mabasic\Kalista\Databases;

use Mabasic\Kalista\VideoFile;

interface Database {

    public function getName(VideoFile $videoFile);

}