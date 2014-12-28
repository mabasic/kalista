<?php  namespace Mabasic\Kalista\Core;

use \Illuminate\Filesystem\Filesystem as IlluminateFilesystem;
use Symfony\Component\Finder\SplFileInfo;

class Filesystem extends IlluminateFilesystem {

    public function getFiles($source, $extensions, $exclusions)
    {
        $files = $this->allFiles($source);

        $files = $this->filterExtensions($files, $extensions);

        return $this->filterExclusions($files, $exclusions);
    }

    private function filterExtensions($files, $extensions)
    {
        return array_filter($files, function (SplFileInfo $file) use ($extensions)
        {
            if ( ! in_array($file->getExtension(), $extensions)) return false;

            return true;
        });
    }

    private function filterExclusions($files, $exclusions)
    {
        return array_filter($files, function (SplFileInfo $file) use ($exclusions)
        {
            foreach ($exclusions as $exclusion)
            {
                if (strpos($file->getFilename(), $exclusion) === false) return true;
            }

            return false;
        });
    }

}