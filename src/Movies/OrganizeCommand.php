<?php namespace Mabasic\Kalista\Movies;

use Mabasic\Kalista\Traits\FilesystemTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OrganizeCommand extends Command {

    use FilesystemTrait;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('organize:movies')
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

        foreach ($items as $item)
        {
            $absolutePath = $source . '/' . $item;

            if (is_dir($absolutePath))
            {
                $this->organizeMovies($absolutePath, $destination, $output);

                continue;
            }

            // copy file to destination
            $this->copyMovieToDestination(new Movie($item, $source), $destination);

            // write output to user
            $output->writeln($item);
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