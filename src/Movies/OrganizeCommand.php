<?php namespace Mabasic\Kalista\Movies;

use Mabasic\Kalista\Traits\FilesystemTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OrganizeCommand extends Command {

    use FilesystemTrait;

    protected $allowed_extensions;

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
        $source = $input->getArgument('source');
        $destination = $input->getArgument('destination');

        $this->organizeMovies($source, $destination, $output);
    }

    public function organizeMovies($source, $destination, OutputInterface $output)
    {
        $items = $this->scanDirectory($source);

        // Separate files from folders
        $files = array_filter($items, function($item) use ($source)
        {
            return ! is_dir($source . '/' . $item);
        });

        foreach($files as $file)
        {
            $movie = new Movie($file, $source);
            
            if( ! in_array($movie->getExtension(), $this->allowed_extensions)) continue;

            $this->copyMovieToDestination($movie, $destination);

            $output->writeln($file);
        }

        $folders = array_diff($items, $files);

        foreach($folders as $folder)
        {
            // Recursive
            $this->organizeMovies($source . '/' . $folder, $destination, $output);
        }
    }

    /**
     * @param Movie $movie
     * @param $destination
     */
    public function copyMovieToDestination(Movie $movie, $destination)
    {
        $target = $movie->getDestinationPath($destination);

        $this->createDirectory($target);

        copy($movie->getFullPath(), $target . '/' . $movie->getFilename());
    }
}