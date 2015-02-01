<?php namespace Mabasic\Kalista\TVShows;

use Mabasic\Kalista\Command;
use Mabasic\Kalista\Databases\TheMovieDB\TvShowEpisodeDatabase;
use Mabasic\Kalista\Providers\TheMovieDBServiceProvider;
use Mabasic\Kalista\VideoFileInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo;

class OrganizeCommand extends Command {

    protected $progress;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('tvshows:organize')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'Source folder that needs to be organized.'
            )
            ->addArgument(
                'destination',
                InputArgument::REQUIRED,
                'Destination folder where organized files are stored.'
            )
            ->addArgument(
                'database',
                InputArgument::OPTIONAL,
                'Database to be used for name resolution. The default is TheMovieDB.'
            )
            ->addOption(
                'testing'
            )
            ->setDescription('Rename and move TV shows from one folder to another folder in separate folders.');
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $input->getArgument('source');
        $destination = $input->getArgument('destination');

        $database = $this->getDatabase($input->getArgument('database'));

        $testing = $input->getOption('testing');

        $tvShowEpisodeCollection = $this->renameTvShowEpisodes($source, $output, $database, $testing, false);

        $this->organizeTvShowEpisodes($tvShowEpisodeCollection, $destination, $output, $testing);
    }

    protected function organizeTvShowEpisodes(TvShowEpisodeCollection $tvShowEpisodesCollection, $destination, OutputInterface $output, $testing = false)
    {
        if ( ! $testing)
            $this->moveFiles($tvShowEpisodesCollection->getCollection(), $destination, $output);

        if (count($tvShowEpisodesCollection->getCollection()) == 0)
        {
            $output->writeln('The are no files to move.');
        }
        else
        {
            $output->writeln('Changes: ');
            $this->outputTable($this->getHeaders(), $this->getRows($tvShowEpisodesCollection->getCollection()), $output);

            $output->writeln('');
            $output->writeln('Unresolved: ');
            $this->outputTable($tvShowEpisodesCollection->getUnresolvedHeaders(), $tvShowEpisodesCollection->getUnresolvedRows(), $output);
        }
    }

    private function getHeaders()
    {
        return ['Old', 'New'];
    }

    private function getRows($collection)
    {
        $rows = [];

        array_walk($collection, function(TvShowEpisode $tvShowEpisode) use (&$rows)
        {
            $rows[] = [
                $tvShowEpisode->file()->getFilename(),
                $tvShowEpisode->getOrganizedFilePathPartial()
            ];
        });

        return $rows;
    }

    private function getDatabase($database)
    {
        /*if($database == 'xy')
        {
            return new
        }*/

        // The default = TheMovieDB
        return new TvShowEpisodeDatabase(new TheMovieDBServiceProvider($this->environmental));
    }

    private function moveFiles($files, $destination, OutputInterface $output)
    {
        $output->writeln('Moving Tv Show episodes to new location');
        $output->writeln('');

        $progress = new ProgressBar($output, count($files));
        //$progress->setMessage('Loading Tv Show episode data from database');
        $progress->start();

        array_walk($files, function(VideoFileInterface $file) use ($destination, $progress)
        {
            $this->filesystem->move($file->getPathname(), $destination . $file->getOrganizedFilePathPartial());

            $progress->advance();
        });

        $progress->finish();

        $output->writeln('');
        $output->writeln('');
        $output->writeln('Task finished.');
        $output->writeln('');
    }
}