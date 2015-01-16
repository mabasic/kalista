<?php namespace Mabasic\Kalista\TvShows;

use Mabasic\Kalista\CollectionInterface;
use Mabasic\Kalista\Databases\Exceptions\TvShowEpisodeNotFoundException;
use Mabasic\Kalista\Databases\TheMovieDB\TvShowEpisodeDatabase;
use Mabasic\Kalista\TvShows\Exceptions\TvShowEpisodeRequiredException;

class TvShowEpisodeCollection implements CollectionInterface {

    protected $tvShowEpisodes = [];

    function __construct($tvShowEpisodes = null)
    {
        if($tvShowEpisodes !== null) $this->addTvShowEpisodes($tvShowEpisodes);
    }

    public function add($tvShowEpisodes)
    {
        if (is_array($tvShowEpisodes))
        {
            return $this->addTvShowEpisodes($tvShowEpisodes);
        }

        if ( ! $tvShowEpisodes instanceof TvShowEpisode)
            throw new TvShowEpisodeRequiredException('Not a TvShowEpisode!');

        $this->tvShowEpisodes[] = $tvShowEpisodes;

        return $this;
    }

    private function addTvShowEpisodes($tvShowEpisodes)
    {
        foreach ($tvShowEpisodes as $tvShowEpisode)
        {
            if ( ! $tvShowEpisode instanceof TvShowEpisode)
                throw new TvShowEpisodeRequiredException('Not a TvShowEpisode!');

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
            catch(TvShowEpisodeNotFoundException $e)
            {
                // TODO: I need to inform the user that these files were not processed.
                // Maybe PubSub or something.
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