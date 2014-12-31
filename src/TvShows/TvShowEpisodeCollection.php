<?php namespace Mabasic\Kalista\TvShows;

use Exception;
use Mabasic\Kalista\Collection;
use Mabasic\Kalista\Databases\TheMovieDB\TvShowEpisodeDatabase;

class TvShowEpisodeCollection implements Collection {

    protected $tvShowEpisodes = [];

    function __construct($tvShowEpisodes)
    {
        $this->addTvShowEpisodes($tvShowEpisodes);
    }

    public function add($tvShowEpisodes)
    {
        if (is_array($tvShowEpisodes))
        {
            return $this->addTvShowEpisodes($tvShowEpisodes);
        }

        if ( ! $tvShowEpisodes instanceof TvShowEpisode)
            throw new Exception('Not a TvShowEpisode!');

        $this->tvShowEpisodes[] = $tvShowEpisodes;

        return $this;
    }

    private function addTvShowEpisodes($tvShowEpisodes)
    {
        foreach ($tvShowEpisodes as $tvShowEpisode)
        {
            if ( ! $tvShowEpisode instanceof TvShowEpisode)
                throw new Exception('Not a TvShowEpisode!');

            $this->add($tvShowEpisode);
        }

        return $this;
    }

    public function fetchTvShowEpisodeInfo(TvShowEpisodeDatabase $database)
    {
        $counter = 0;

        foreach($this->tvShowEpisodes as $tvShowEpisode)
        {
            try
            {
                $tvShowEpisode->setName($database->getName($tvShowEpisode));
                $tvShowEpisode->setShowName($database->getShowName($tvShowEpisode));
            }
            catch(Exception $e)
            {
                // TODO: I need to inform the user that these files were not processed.
                $this->remove($counter);
            }

            $counter++;
        }

        return $this;
    }

    public function getCollection()
    {
        return $this->tvShowEpisodes;
    }

    public function getHeaders()
    {
        return ['Filename', 'Episode Name', 'Episode number', 'Show Name', 'Season'];
    }

    public function getRows()
    {
        $rows = [];

        foreach ($this->tvShowEpisodes as $tvShowEpisode)
        {
            $rows[] = [
                $tvShowEpisode->file()->getFilename(),
                $tvShowEpisode->getName(),
                $tvShowEpisode->getEpisodeNumber(),
                $tvShowEpisode->getShowName(),
                $tvShowEpisode->getSeason()
            ];
        }

        return $rows;
    }

    public function remove($index)
    {
        unset($this->tvShowEpisodes[$index]);
    }
}