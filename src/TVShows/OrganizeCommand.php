<?php namespace Mabasic\Kalista\TVShows;

use Mabasic\Kalista\Traits\FilesystemTrait;
use Symfony\Component\Console\Helper\ProgressBar;
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
            ->setDescription('Moves TV shows from one folder to another folder in separate folders.');
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

        $this->organizeTVShows($source, $destination, $output);
    }

    public function organizeTVShows($source, $destination, OutputInterface $output)
    {
        $items = $this->scanDirectory($source);

        // Separate files from folders
        $files = array_filter($items, function($item) use ($source)
        {
            return ! is_dir($source . '/' . $item);
        });

        $progress = new ProgressBar($output, count($files));

        $progress->start();

        foreach($files as $file)
        {
            $tvShow = new TVShow($file, $source);

            if( ! in_array($tvShow->getExtension(), $this->allowed_extensions)) continue;

            $this->copyTVShowToDestination($tvShow, $destination);

            $progress->advance();

            //$output->writeln($file);
        }

        $progress->finish();

        $folders = array_diff($items, $files);

        foreach($folders as $folder)
        {
            // Recursive
            $this->organizeTVShows($source . '/' . $folder, $destination, $output);
        }
    }

    /**
     * @param TVShow $tvShow
     * @param $destination
     */
    public function copyTVShowToDestination(TVShow $tvShow, $destination)
    {
        $target = $tvShow->getDestinationPath($destination);

        $this->createDirectory($target);

        copy($tvShow->getFullPath(), $target . '/' . $tvShow->getFilename());
    }
}