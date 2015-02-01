<?php namespace Mabasic\Kalista\TvShows;

use Exception;
use Mabasic\Kalista\CollectionInterface;
use Mabasic\Kalista\Databases\Exceptions\TvShowEpisodeNotFoundException;
use Mabasic\Kalista\Databases\TheMovieDB\TvShowEpisodeDatabase;
use Mabasic\Kalista\TvShows\Exceptions\TvShowEpisodeRequiredException;
use Mabasic\Kalista\VideoFileInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class TvShowEpisodeCollection implements CollectionInterface {

    protected $tvShowEpisodes = [];

    protected $unresolved = [];

    protected $pending = [];

    function __construct($tvShowEpisodes = null)
    {
        if ($tvShowEpisodes !== null) $this->addTvShowEpisodes($tvShowEpisodes);
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

    public function fetchTvShowEpisodeInfo(TvShowEpisodeDatabase $database, OutputInterface $output)
    {
        $output->writeln('Loading Tv Show episode data from database');
        $output->writeln('');

        $progress = new ProgressBar($output, count($this->tvShowEpisodes));
        //$progress->setMessage('Loading Tv Show episode data from database');
        $progress->start();

        array_walk($this->tvShowEpisodes, function (TvShowEpisode $tvShowEpisode) use (&$counter, $database, $progress, $output)
        {
            try
            {
                $tvShowEpisode->setName($database->getName($tvShowEpisode));
                $tvShowEpisode->setShowName($database->getShowName($tvShowEpisode));
            }
            catch (TvShowEpisodeNotFoundException $e)
            {
                $this->addToPending($tvShowEpisode->file()->getFilename());
            }

            $progress->advance();
        });

        $this->sendPendingToUnresolved();

        //$progress->setMessage('Task finished.');
        $progress->finish();

        $output->writeln('');
        $output->writeln('');
        $output->writeln('Task finished.');
        $output->writeln('');

        return $this;
    }

    public function getCollection()
    {
        return $this->tvShowEpisodes;
    }

    /**
     * @return array
     */
    public function getUnresolved()
    {
        return $this->unresolved;
    }

    public function getHeaders()
    {
        return ['Filename', 'Episode Name', 'Episode number', 'Show Name', 'Season'];
    }

    public function getUnresolvedHeaders()
    {
        return ['Location'];
    }

    public function getRows()
    {
        $rows = [];

        array_walk($this->tvShowEpisodes, function (TvShowEpisode $tvShowEpisode) use (&$rows)
        {
            $rows[] = [
                $tvShowEpisode->file()->getFilename(),
                $tvShowEpisode->getName(),
                $tvShowEpisode->getEpisodeNumber(),
                $tvShowEpisode->getShowName(),
                $tvShowEpisode->getSeason()
            ];
        });

        return $rows;
    }

    public function getUnresolvedRows()
    {
        $rows = [];

        array_walk($this->unresolved, function (TvShowEpisode $tvShowEpisode) use (&$rows)
        {
            $rows[] = [
                $tvShowEpisode->file()->getPathname() . '\\' . $tvShowEpisode->file()->getFilename()
            ];
        });

        return $rows;
    }

    public function sendPendingToUnresolved()
    {
        array_walk($this->pending, function($item)
        {
            // Get index from original filename
            $index = $this->getIndexByFilename($item);

            // This should never trigger
            if( ! $index) throw new Exception('Could not find index.');

            $this->addToUnresolved($this->tvShowEpisodes[$index]);

            // Remove from main collection
            $this->remove($index);
        });
    }

    public function addToUnresolved(VideoFileInterface $tvShowEpisode)
    {
        $this->unresolved[] = $tvShowEpisode;
    }

    public function addToPending($filename)
    {
        $this->pending[] = $filename;
    }

    private function getIndexByFilename($filename)
    {
        $index = false;

        for ($i = 0; $i < count($this->tvShowEpisodes); $i ++)
        {
            if ($this->tvShowEpisodes[$i]->file()->getFilename() == $filename)
            {
                $index = $i;
                break;
            }
        }

        return $index;
    }

    public function remove($index)
    {
        array_splice($this->tvShowEpisodes, $index, 1);
    }
}