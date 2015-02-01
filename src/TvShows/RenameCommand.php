<?php namespace Mabasic\Kalista\TvShows;

use Mabasic\Kalista\Databases\TheMovieDB\TvShowEpisodeDatabase;
use Mabasic\Kalista\Providers\TheMovieDBServiceProvider;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mabasic\Kalista\Command;

class RenameCommand extends Command {

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('tvshows:rename')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'Source folder of tv shows to be renamed.'
            )
            ->addArgument(
                'database',
                InputArgument::OPTIONAL,
                'Database to be used for name resolution. The default is TheMovieDB.'
            )
            ->addOption(
                'testing'
            )
            ->setDescription('Fetches tv show and episode names and renames files.');
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

        $database = $this->getDatabase($input->getArgument('database'));

        $testing = $input->getOption('testing');

        $this->renameTvShowEpisodes($source, $output, $database, $testing);
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

}