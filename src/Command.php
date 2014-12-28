<?php namespace Mabasic\Kalista;

use Illuminate\Filesystem\Filesystem;
use Mabasic\Kalista\Movies\Movie;
use Mabasic\Kalista\Services\FileBot\FileBot;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo;

class Command extends SymfonyCommand {

    protected $allowedExtensions;

    protected $filebot;

    protected $filesystem;

    /**
     * @param array $allowedExtensions
     * @param Filesystem $filesystem
     */
    public function __construct(array $allowedExtensions, Filesystem $filesystem)
    {
        $this->allowedExtensions = $allowedExtensions;
        $this->filesystem = $filesystem;

        parent::__construct();
    }


    protected function renameFiles($files)
    {
        array_walk($files, function (Movie $file)
        {
            $this->renameFile($file);
        });
    }

    protected function renameFile(Movie $file)
    {
        if ($file->title === null ||
            ! $this->filesystem->move(
                $file->file->getPathname(),
                $file->getModifiedPath())
        ) return false;

        $file->renamed = true;

        return true;
    }

    protected function renameMovies($source, OutputInterface $output, $moviesApi)
    {
        // What do i want to do in as few steps as possible?

        // 1. get movies from source
        //  - get files
        //      - filter extensions
        //      - filter exclusions
        //  - convert file to movie
        //  - convert files to movie collection
        //  - fetch movie name for each movie
        //  - return only movies that have been resolved

        // 2. rename source files
        // - rename filename to match movie name in filesystem

        // 3. show output
        //  - show table output

        $files = $this->getFiles($source);

        $files = $this->getMappedFiles($files);

        array_walk($files, function (Movie $file) use ($moviesApi)
        {
            $title = $this->cleanFilenameForMovie($file->file->getFilename());

            $file->setTitle($moviesApi->getMovieTitle($title));
        });

        $this->renameFiles($files);

        $table = new Table($output);

        $table->setHeaders(array('Old', 'New', 'Cleaned', 'Renamed'))
            ->setRows($this->createRowsForMovies($files));

        $table->render();

        //$this->outputTable()

        return $files;
    }

    public function outputTable($headers, $rows, OutputInterface $output)
    {
        $table = new Table($output);

        $table
            ->setHeaders($headers)
            ->setRows($rows);

        $table->render();
    }
}