<?php namespace Mabasic\Kalista\Movies;

use Illuminate\Filesystem\Filesystem;
use Mabasic\Kalista\Services\FileBot\FileBot;
use Mabasic\Kalista\Services\TheMovieDB\Movies;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mabasic\Kalista\Command;

class RenameCommand extends Command {

    protected $progress;

    protected $moviesApi;

    public function __construct(array $allowed_extensions, FileBot $filebot, Filesystem $filesystem, Movies $moviesApi)
    {
        $this->moviesApi = $moviesApi;

        parent::__construct($allowed_extensions, $filebot, $filesystem);
    }

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('movies:rename')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'Source folder of movies to be renamed.'
            )
            ->setDescription('Fetches movies names and renames files.');
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
        $this->progress = new ProgressBar($output);

        $source = $input->getArgument('source');

        $this->renameMovies($source, $output, $this->moviesApi);
    }

}