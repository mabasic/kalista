<?php namespace Mabasic\Kalista;

use Illuminate\Filesystem\Filesystem;
use Mabasic\Kalista\Movies\Movie;
use Mabasic\Kalista\Services\FileBot\FileBot;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo;

class Command extends SymfonyCommand {

    protected $allowed_extensions;

    protected $filebot;

    protected $filesystem;

    /**
     * @param array $allowed_extensions
     * @param FileBot $filebot
     * @param Filesystem $filesystem
     */
    public function __construct(array $allowed_extensions, FileBot $filebot, Filesystem $filesystem)
    {
        $this->allowed_extensions = $allowed_extensions;
        $this->filebot = $filebot;
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

        return $files;
    }
}