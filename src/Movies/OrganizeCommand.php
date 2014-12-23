<?php namespace Mabasic\Kalista\Movies;

use Mabasic\Kalista\Traits\FilesystemTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OrganizeCommand extends Command {

    use FilesystemTrait;

    protected $allowed_extensions;

    protected $source;

    protected $destination;

    protected $output;

    /**
     * @param array $allowed_extensions
     */
    public function __construct(array $allowed_extensions)
    {
        $this->allowed_extensions = $allowed_extensions;

        parent::__construct();
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
            ->setDescription('Movies movies from one folder in another folder in separate folders.');
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
        $this->source = $input->getArgument('source');
        $this->destination = $input->getArgument('destination');
        $this->output = $output;

        $this->organizeMovies($this->source);
    }

    /**
     * @param $source
     */
    public function organizeMovies($source)
    {
        $items = $this->scanDirectory($source);

        $files = $this->filterFilesFromFolders($source, $items);

        $this->copyMoviesToDestination($source, $files);

        $this->processFoldersRecursive($source, $items, $files);
    }

    /**
     * @param Movie $movie
     */
    public function copyMovieToDestination(Movie $movie)
    {
        $target = $movie->getDestinationPath($this->destination);

        $this->createDirectory($target);

        copy($movie->getFullPath(), $target . '/' . $movie->getFilename());
    }

    /**
     * Returns an array of files from given items.
     *
     * @param $source
     * @param $items
     * @return array
     */
    private function filterFilesFromFolders($source, $items)
    {
        return array_filter($items, function ($item) use ($source)
        {
            return ! is_dir($source . '/' . $item);
        });
    }

    /**
     * Copies files from source to destination and writes to output.
     * Before doing anything it checks if the file extension is allowed.
     *
     * @param $source
     * @param $files
     */
    private function copyMoviesToDestination($source, $files)
    {
        foreach ($files as $file)
        {
            $movie = new Movie($file, $source);

            if ( ! in_array($movie->getExtension(), $this->allowed_extensions)) continue;

            $this->copyMovieToDestination($movie, $this->destination);

            $this->output->writeln($file);
        }
    }

    /**
     * Given all items and files it finds folders and
     * then it searches them for files and calls organize
     * movies method.
     *
     * @param $source
     * @param $items
     * @param $files
     */
    private function processFoldersRecursive($source, $items, $files)
    {
        $folders = array_diff($items, $files);

        foreach ($folders as $folder)
        {
            // Recursive
            $this->organizeMovies($source . '/' . $folder);
        }
    }
}