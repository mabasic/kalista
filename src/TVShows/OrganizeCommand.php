<?php namespace Mabasic\Kalista\TVShows;

use Mabasic\Kalista\Command;
use Mabasic\Kalista\TVShows\Exceptions\UnreadableTVShowInformationException;
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
        $this->progress = new ProgressBar($output);

        $source = $input->getArgument('source');
        $destination = $input->getArgument('destination');

        $this->organizeTVShows($source, $destination, $output);
    }

    public function organizeTVShows($source, $destination, OutputInterface $output)
    {
        $files = $this->getFiles($source);

        $numberOfFiles = count($files);

        if ($numberOfFiles == 0)
        {
            return $output->writeln('There are no TVShows to be organized.');
        }

        $this->progress->start($numberOfFiles);

        $this->filebot->renameTVShows($files);

        $files = $this->getFiles($source);

        $this->moveTVShowsToDestination($files, $destination);

        // TODO: Why does this method do nothing???
        $this->filesystem->cleanDirectory($source);

        $this->progress->finish();
    }

    private function moveTVShowsToDestination($files, $destination)
    {
        array_walk($files, function (SplFileInfo $file) use ($destination)
        {
            $destinationFolder = $destination . '\\' . $this->getFolderNameForTVShow($file);

            $this->makeDirectory($destinationFolder);


            $destinationMoviePath = $destinationFolder . '\\' . $file->getFilename();

            $this->filesystem->move($file->getPathname(), $destinationMoviePath);

            $this->progress->advance();
        });
    }

    private function getFolderNameForTVShow(SplFileInfo $tvShow)
    {
        $exploded = explode(' - ', $tvShow->getFilename());

        if(count($exploded) != 3)
        {
            throw new UnreadableTVShowInformationException($tvShow->getFilename());
        }

        $season = explode('x', $exploded[1]);

        $season = $season[0];
        //$episode = $season[1];
        //$episodeTitle = $exploded[2];
        $tvShowTitle = $exploded[0];

        return $tvShowTitle . '\\Season ' . $season;
    }
}