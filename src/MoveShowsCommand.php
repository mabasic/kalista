<?php namespace Mabasic\Kalista;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MoveShowsCommand extends Command
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
        ->setName('move:shows')
        ->setDescription('Move shows from source to destination.')
        ->addArgument(
            'source',
            InputArgument::REQUIRED,
            'From where do you want to move shows from?'
        )
        ->addArgument(
            'destination',
            InputArgument::REQUIRED,
            'Where do you want to move shows to?'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $progressBar = new ProgressBar($output);

        $source = $input->getArgument('source');
        $destination = $input->getArgument('destination');

        $files = $this->filesystem->allFiles($source);

        $progressBar->start(count($files));

        array_walk($files, function (SplFileInfo $file) use ($destination, $progressBar) {
            // Fear The Walking Dead - 1x02 - So Close, Yet So Far.mp4
            // Grabs the show name (Fear The Walking Dead)
            $destinationFolder = explode(' - ', $file->getFilename())[0];

            // Created folder for the show using recursion (because of root folder if it does not exist)
            $this->filesystem->makeDirectory($destination . '\\' . $destinationFolder, 0755, true, true);

            // Move files from source to destination
            $this->filesystem->move($file->getPathname(), $destination . '\\' . $destinationFolder . '\\' . $file->getFilename());

            $progressBar->advance();
        });

        $progressBar->finish();

        $output->writeln('Shows have been moved!');
    }
}
