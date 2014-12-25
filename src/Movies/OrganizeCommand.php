<?php namespace Mabasic\Kalista\Movies;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo;
use Mabasic\Kalista\Command;

class OrganizeCommand extends Command {

    protected $progress;

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

    private function getFolderNameForMovie(SplFileInfo $movie)
    {
        // If filename is already formatted
        // return file name
        $output = explode(' [', $movie->getFilename())[0];

        // If filename is not formatted
        if ($movie->getFilename() == $output)
        {
            // Return file name without extension
            $output = explode('.', $movie->getFilename())[0];
        }

        return $output;
    }

    /**
     * @param $files
     * @param $destination
     */
    private function moveMoviesToDestination($files, $destination)
    {
        array_walk($files, function (SplFileInfo $file) use ($destination)
        {
            $destinationFolder = $destination . '\\' . $this->getFolderNameForMovie($file);

            $this->makeDirectory($destinationFolder);


            $destinationMoviePath = $destinationFolder . '\\' . $file->getFilename();

            $this->filesystem->move($file->getPathname(), $destinationMoviePath);

            $this->progress->advance();
        });
    }

    private function organizeMovies($source, $destination, OutputInterface $output)
    {
        $files = $this->getFiles($source);

        $numberOfFiles = count($files);

        if ($numberOfFiles == 0)
        {
            return $output->writeln('There are no movies to be organized.');
        }

        $this->progress->start($numberOfFiles);

        $this->filebot->renameMovies($files);

        $files = $this->getFiles($source);

        $this->moveMoviesToDestination($files, $destination);

        // TODO: Why does this method do nothing???
        $this->filesystem->cleanDirectory($source);

        $this->progress->finish();
    }
}