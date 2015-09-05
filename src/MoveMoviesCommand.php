<?php namespace Mabasic\Kalista;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MoveMoviesCommand extends Command
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        // Call SymfonyCommand constructor
        parent::__construct();
    }

    protected function configure()
    {
        $this
        ->setName('move:movies')
        ->setDescription('Move movies from one location to another.')
        ->addArgument(
            'source',
            InputArgument::REQUIRED,
            'From where do you want to move movies?'
        )
        ->addArgument(
            'destination',
            InputArgument::REQUIRED,
            'Where do you want to move movies to?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $input->getArgument('source');
        $destination = $input->getArgument('destination');

        $files = $this->filesystem->allFiles($source);

        array_walk($files, function (SplFileInfo $file) use ($destination) {
            // Mortdecai [2015, R, 5.4].mkv
            // Removes this part ` [2015, R, 5.4].mkv`
            $destinationFolder = preg_replace('( \\[.*?\\]|.avi|.mp4|.mkv)', '', $file->getFilename());

            // Created folder for the movie using recursion (because of root folder if it does not exist)
            $this->filesystem->makeDirectory($destination . '\\' . $destinationFolder, 0755, true, true);

            // Move files from source to destination
            $this->filesystem->move($file->getPathname(), $destination . '\\' . $destinationFolder . '\\' . $file->getFilename());
        });

        $output->writeln($files);
    }
}
