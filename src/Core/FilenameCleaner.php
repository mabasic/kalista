<?php namespace Mabasic\Kalista\Core;

class FilenameCleaner {


    /**
     * Cleans filename of common characters and given regex.
     * Use this if you want to get clean filename.
     *
     * @param $filename
     * @param $regex
     * @return string
     */
    public function cleanFilename($filename, $regex)
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