<?php namespace Mabasic\Kalista\Movies;

use Illuminate\Filesystem\Filesystem;
use Mabasic\Kalista\Services\FileBot\FileBot;
use Mabasic\Kalista\Services\TheMovieDB\Movies;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Mabasic\Kalista\Command;

class OrganizeCommand extends Command {

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
        $this->setName('movies:organize')
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
            ->setDescription('Moves movies from one folder in another folder in separate folders.');
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
        $destination = $input->getArgument('destination');

        $this->organizeMovies($source, $destination, $output);
    }

    private function organizeMovies($source, $destination, OutputInterface $output)
    {
        $files = $this->renameMovies($source, $output, $this->moviesApi);

        $this->progress->start(count($files));

        $this->moveMoviesToDestination($files, $destination);

        // TODO: Why does this method do nothing???
        $this->filesystem->cleanDirectory($source);

        $this->progress->finish();
    }

    /**
     * @param $files
     * @param $destination
     */
    private function moveMoviesToDestination($files, $destination)
    {
        array_walk($files, function (Movie $file) use ($destination)
        {
            $destinationFolder = $destination . '\\' . $this->getFolderNameForMovie($file);

            $this->makeDirectory($destinationFolder);


            $destinationMoviePath = $destinationFolder . '\\' . $file->getModifiedFilename();

            $this->filesystem->move($file->getModifiedPath(), $destinationMoviePath);

            $this->progress->advance();
        });
    }

    private function getFolderNameForMovie(Movie $movie)
    {
        // If filename is already formatted
        // return file name
        $output = explode(' [', $movie->getModifiedFilename())[0];

        // If filename is not formatted
        if ($movie->getModifiedFilename() == $output)
        {
            // Return file name without extension
            $output = explode('.', $movie->getModifiedFilename())[0];
        }

        return $output;
    }
}