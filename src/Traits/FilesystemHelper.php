<?php  namespace Mabasic\Kalista\Traits;

use Mabasic\Kalista\Traits\Exceptions\NameNotSetException;
use Mabasic\Kalista\VideoFileInterface;
use SplFileInfo;

trait FilesystemHelper {

    /**
     * It checks if the given directory exists and if
     * it does not exist it creates it. RECURSIVE
     *
     * @param $path
     */
    public function makeDirectory($path)
    {
        if ( ! $this->filesystem->exists($path))
        {
            $this->filesystem->makeDirectory($path, 0755, true);
        }
    }

    public function renameFiles($files)
    {
        array_walk($files, function(VideoFileInterface $file)
        {
            if($file->getName() === null)
                throw new NameNotSetException('Name not set!');

            $this->filesystem->move($file->file()->getPathname(), $file->getPathname());
        });

    }

    public function getFiles($path, $extensions = ['avi', 'mp4', 'mkv'], $ignoredWords = ['Sample'])
    {
        $files = $this->filesystem->allFiles($path);

        $files = $this->removeFilesThatContainIgnoredWords($files, $ignoredWords);

        return $this->getFilesWithExtensions($files, $extensions);
    }

    private function getFilesWithExtensions($files, $extensions)
    {
        return array_filter($files, function (SplFileInfo $file) use ($extensions)
        {
            if ( ! in_array($file->getExtension(), $extensions)) return false;

            return true;
        });
    }

    private function removeFilesThatContainIgnoredWords($files, $ignoredWords)
    {
        return array_filter($files, function (SplFileInfo $file) use ($ignoredWords)
        {
            foreach ($ignoredWords as $ignoredWord)
            {
                if (strpos($file->getFilename(), $ignoredWord) === false) return true;
            }

            return false;
        });
    }

}