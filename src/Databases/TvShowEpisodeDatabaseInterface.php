<?php  namespace Mabasic\Kalista\Databases;

use Mabasic\Kalista\VideoFileInterface;

interface TvShowEpisodeDatabaseInterface {

    public function getShowName(VideoFileInterface $videoFile);

}