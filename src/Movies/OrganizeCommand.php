<?php namespace Mabasic\Kalista\Movies;

use Illuminate\Filesystem\Filesystem;
use Mabasic\Kalista\Services\FileBot\FileBot;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo;

class OrganizeCommand extends Command {

    protected $allowed_extensions;

    protected $destination;

    protected $output;

    protected $filebot;

    protected $progress;

    /**
     * @param array $allowed_extensions
     */
    public function __construct(array $allowed_extensions)
    {
        $this->allowed_extensions = $allowed_extensions;
        $this->filebot = new FileBot;
        $this->filesystem = new Filesystem;

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
        $this->destination = $input->getArgument('destination');
        $this->output = $output;

        $files = $this->getFiles($source);

        $numberOfFiles = count($files);

        if ($numberOfFiles > 0)
        {
            $this->progress = new ProgressBar($output, $numberOfFiles);

            $this->progress->start();
        }

        $this->filebot->renameMovies($files);

        $files = $this->getFiles($source);

        $this->moveMoviesToDestination($files);

        // TODO: Why does this method do nothing???
        $this->filesystem->cleanDirectory($source);

        if ($numberOfFiles > 0)
        {
            $this->progress->finish();
        } else
        {
            $this->output->writeln('There are no movies to be organized.');
        }
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

    private function getFiles($source)
    {
        $files = $this->filesystem->allFiles($source);

        $files = $this->filterSampleFiles($files);

        return $this->filterAllowedExtensions($files);
    }

    private function filterAllowedExtensions($files)
    {
        return array_filter($files, function (SplFileInfo $file)
        {
            if ( ! in_array($file->getExtension(), $this->allowed_extensions)) return false;

            return true;
        });
    }

    private function filterSampleFiles($files)
    {
        return array_filter($files, function (SplFileInfo $file)
        {
            if (strpos($file->getFilename(), 'Sample') === false) return true;

            return false;
        });
    }

    /**
     * @param $folderPath
     */
    private function makeDirectory($folderPath)
    {
        if ( ! $this->filesystem->exists($folderPath))
        {
            $this->filesystem->makeDirectory($folderPath);
        }
    }

    /**
     * @param $files
     */
    private function moveMoviesToDestination($files)
    {
        array_walk($files, function (SplFileInfo $file)
        {
            $destinationFolder = $this->destination . '\\' . $this->getFolderNameForMovie($file);

            $this->makeDirectory($destinationFolder);


            $destinationMoviePath = $destinationFolder . '\\' . $file->getFilename();

            $this->filesystem->move($file->getPathname(), $destinationMoviePath);

            $this->progress->advance();
        });
    }
}