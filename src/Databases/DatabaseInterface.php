<?php namespace Mabasic\Kalista\Databases;

use Mabasic\Kalista\VideoFileInterface;

interface DatabaseInterface {

    public function getName(VideoFileInterface $videoFile);

}