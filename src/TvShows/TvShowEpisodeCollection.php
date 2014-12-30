<?php namespace Mabasic\Kalista\TvShows;

use Exception;
use Mabasic\Kalista\Collection;
use Mabasic\Kalista\Databases\Database;
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
        array_walk($this->tvShowEpisodes, function(TvShowEpisode $tvShowEpisode) use ($database)
        {
            $tvShowEpisode->setName($database->getName($tvShowEpisode));
            $tvShowEpisode->setShowName($database->getShowName($tvShowEpisode));
        });

        return $this;
    }

    public function getCollection()
    {
        return $this->tvShowEpisodes;
    }

    public function getHeaders()
    {
        return ['Filename', 'TvShowEpisode Name', 'TvShow Name'];
    }

    public function getRows()
    {
        $rows = [];

        foreach ($this->tvShowEpisodes as $tvShowEpisode)
        {
            $rows[] = [
                $tvShowEpisode->file()->getFilename(),
                $tvShowEpisode->getName(),
                $tvShowEpisode->getShowName()
            ];
        }

        return $rows;
    }
}