<?php  namespace Mabasic\Kalista\Cleaners;

use Exception;

abstract class FilenameCleaner implements Cleaner {

    /**
     * Cleans filename of common characters and given regex.
     * Use this if you want to get clean filename.
     *
     * @param $filename
     * @param $regex
     * @return string
     * @throws Exception
     */
    public function prepare($filename, $regex)
    {
        $value = preg_replace('(\\[.*?\\])', '', $filename);

        $words = preg_split('/[.]/', $value);

        $words = array_filter($words, function ($word) use ($regex)
        {
            return ! (preg_match($regex, $word));
        });

        $output = join(' ', $words);

        if($output == "")
            throw new Exception("Could not clean filename: {$filename}");

        return $output;
    }
}