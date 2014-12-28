<?php namespace Mabasic\Kalista\Core;

use Symfony\Component\Finder\SplFileInfo;

class FileFilterer {

    public function filterAllowedExtensions($files, array $extensions = ['avi', 'mp4', 'mkv'])
    {
        return array_filter($files, function (SplFileInfo $file) use ($extensions)
        {
            if ( ! in_array($file->getExtension(), $extensions)) return false;

            return true;
        });
    }

    public function filterSampleFiles($files, array $samples = ['Sample'])
    {
        return array_filter($files, function (SplFileInfo $file) use ($samples)
        {
            foreach ($samples as $sample)
            {
                if (strpos($file->getFilename(), $sample) === false) return true;
            }

            return false;
        });
    }

}