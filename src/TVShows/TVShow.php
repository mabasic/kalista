<?php  namespace Mabasic\Kalista\TVShows;

use Mabasic\Kalista\File;
//use Mabasic\Kalista\TVShows\Exceptions\UnreadableTVShowInformationException;

class TVShow extends File {

    protected $season;

    protected $episodeTitle;

    protected $episode;

    protected $tvShowTitle;

    public function __construct($filename, $path)
    {
        $this->filename = $filename;
        $this->path = $path;

        $this->detectInformation();
    }

    public function getDestinationPath($destination)
    {
        return $destination . '/' . $this->tvShowTitle . '/Season ' . $this->season;
    }

    private function detectInformation()
    {
        $exploded = explode(' - ', $this->getFilename());

        if(count($exploded) != 3)
        {
            //throw new UnreadableTVShowInformationException($this->getFilename());
            return false;
        }

        $season = explode('x', $exploded[1]);

        $this->season = $season[0];
        $this->episode = $season[1];
        $this->episodeTitle = $exploded[2];
        $this->tvShowTitle = $exploded[0];

        return true;
    }

}