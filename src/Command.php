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

    protected function filterAllowedExtensions($files)
    {
        return array_filter($files, function (SplFileInfo $file)
        {
            if ( ! in_array($file->getExtension(), $this->allowed_extensions)) return false;

            return true;
        });
    }

    protected function filterSampleFiles($files)
    {
        return array_filter($files, function (SplFileInfo $file)
        {
            if (strpos($file->getFilename(), 'Sample') === false) return true;

            return false;
        });
    }

    protected function getFiles($source)
    {
        $files = $this->filesystem->allFiles($source);

        $files = $this->filterSampleFiles($files);

        return $this->filterAllowedExtensions($files);
    }

    public function getMappedFiles($files)
    {
        return array_map(function($file)
        {
            return new Movie($file);
        }, $files);
    }

    /**
     * @param $folderPath
     */
    protected function makeDirectory($folderPath)
    {
        if ( ! $this->filesystem->exists($folderPath))
        {
            $this->filesystem->makeDirectory($folderPath, 0755, true);
        }
    }

    private function cleanFilename($filename, $regex)
    {
        $value = preg_replace('(\\[.*?\\])', '', $filename);

        $words = preg_split('/[.]/', $value);

        $words = array_filter($words, function ($word) use ($regex)
        {
            return ! (preg_match($regex, $word));
        });

        $output = join(' ', $words);

        return $output;
    }

    protected function cleanFilenameForMovie($filename)
    {
        return $this->cleanFilename($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|2014/i");
    }

    protected function cleanFilenameForTvShow($filename)
    {
        return $this->cleanFilename($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|[0-9]/i");
    }

    protected function renameFiles($files)
    {
        array_walk($files, function(Movie $file)
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

    protected function createRowsForMovies($files)
    {
        $rows = [];

        foreach ($files as $file)
        {
            $rows[] = [
                $file->file->getFilename(),
                $file->title,
                $file->cleaned,
                $file->renamed
            ];
        }

        return $rows;
    }

    /**
     * @param $files
     * @return array
     */
    protected function getRenamedMoviesOnly($files)
    {
        $files = array_filter($files, function (Movie $movie)
        {
            return $movie->cleaned;
        });

        return $files;
    }
}