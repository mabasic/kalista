<?php  namespace Mabasic\Kalista\Movies;

use Mabasic\Kalista\File;

class Movie extends File {

    public function __construct($filename, $path)
    {
        $this->filename = $filename;
        $this->path = $path;
    }

    public function detectMovieName()
    {
        // If filename is already formatted
        // return file name
        $output = explode('[', $this->getFilename())[0];

        // If filename is not formatted
        if($this->getFilename() == $output)
        {
            // Return file name without extension
            $output = explode('.', $this->getFilename())[0];
        }

        return $output;
    }

    public function getDestinationPath($destination)
    {
        return $destination . '/' . $this->detectMovieName();
    }

}