<?php namespace Mabasic\Kalista;

use Illuminate\Filesystem\Filesystem;
use Mabasic\Kalista\Services\FileBot\FileBot;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
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

        var_dump($output);

        return $output;
    }

    protected function cleanFilenameForMovie($filename)
    {
        return $this->cleanFilename($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|2014|410/i");
    }

    protected function cleanFilenameForTvShow($filename)
    {
        return $this->cleanFilename($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|[0-9]/i");
    }
}