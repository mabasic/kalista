<?php namespace Mabasic\Kalista;

use Illuminate\Filesystem\Filesystem;
use Mabasic\Kalista\Databases\Database;
use Mabasic\Kalista\Mappers\Mapper;
use Mabasic\Kalista\Movies\MovieCollection;
use Mabasic\Kalista\Movies\MovieFilenameCleaner;
use Mabasic\Kalista\Services\Environmental\Environmental;
use Mabasic\Kalista\Traits\FilesystemHelper;
use Mabasic\Kalista\TvShows\TvShowEpisodeCollection;
use Mabasic\Kalista\TvShows\TvShowEpisodeFilenameCleaner;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends SymfonyCommand {

    use FilesystemHelper;

    protected $filesystem;

    protected $environmental;

    protected $mapper;

    public function __construct(Filesystem $filesystem, Environmental $environmental, Mapper $mapper)
    {
        $this->filesystem = $filesystem;
        $this->environmental = $environmental;
        $this->mapper = $mapper;

        // Call SymfonyCommand constructor
        parent::__construct();
    }

    protected function renameMovies($source, OutputInterface $output, Database $database)
    {
        // What do i want to do in as few steps as possible?

        /**
         * filesystem helper get files (allowed and without sample files
         *
         * Map files to Movies
         *
         * fetch movie names using database
         *
         * rename files using filesystem helper
         *
         * output table
         */

        $files = $this->getFiles($source);

        $movies = $this->mapper->mapFiles($files, 'Mabasic\Kalista\Movies\Movie', new MovieFilenameCleaner);

        $moviesCollection = (new MovieCollection($movies))->fetchMovieNames($database);

        $this->renameFiles($moviesCollection->getCollection());

        $this->outputTable($moviesCollection->getHeaders(), $moviesCollection->getRows(), $output);
    }

    protected function renameTvShowEpisodes($source, $output, $database)
    {
        $files = $this->getFiles($source);

        $tvShowEpisodes = $this->mapper->mapFiles($files, 'Mabasic\Kalista\TvShows\TvShowEpisode', new TvShowEpisodeFilenameCleaner);

        $tvShowEpisodesCollection = (new TvShowEpisodeCollection($tvShowEpisodes))->fetchTvShowEpisodeInfo($database);

        $this->renameFiles($tvShowEpisodesCollection->getCollection());

        $this->outputTable($tvShowEpisodesCollection->getHeaders(), $tvShowEpisodesCollection->getRows(), $output);
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