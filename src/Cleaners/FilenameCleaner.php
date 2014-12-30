<?php  namespace Mabasic\Kalista\Cleaners;

abstract class FilenameCleaner implements Cleaner {

    /**
     * Cleans filename of common characters and given regex.
     * Use this if you want to get clean filename.
     *
     * @param $filename
     * @param $regex
     * @return string
     */
    public function prepare($filename, $regex)
    {
        $value = preg_replace('(\\[.*?\\])', '', $filename);

        $words = preg_split('/[.]/', $value);

        $words = array_filter($words, function ($word) use ($regex)
        {
            return ! (preg_match($regex, $word));
        });

        return join(' ', $words);
    }
}