<?php namespace Mabasic\Kalista\Movies;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo;
use Mabasic\Kalista\Command;

class RenameCommand extends Command {

    protected $progress;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('movies:rename')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'Source folder of movies to be renamed.'
            )
            ->addArgument(
                'database',
                InputArgument::OPTIONAL,
                'Database to be used for resolving movie names.'
            )
            ->setDescription('Fetches movies names and renames files.');
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
        $database = $input->getArgument('database');

        $this->renameMovies($source, $database, $output);
    }

    private function renameMovies($source, $database, OutputInterface $output)
    {
        $files = $this->getFiles($source);

        $numberOfFiles = count($files);

        if ($numberOfFiles == 0)
        {
            return $output->writeln('There are no movies to be renamed.');
        }

        if ($database !== null)
        {
            $this->filebot->renameMovies($files, $database);
        }
        else
        {
            $this->filebot->renameMovies($files);
        }

        $output->writeln('All movies are most likely renamed successfully.');

    }
}