<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Filesystem\Filesystem;
use Mabasic\Kalista\Providers\TheMovieDBServiceProvider;
use Mabasic\Kalista\Services\Environmental\Environmental;
use Mabasic\Kalista\Services\TheMovieDB\Movies;

$movies = [
    'Horrible Bosses 2 [2014, R, 7.0].avi',
    'Penguins.of.Madagascar.2014.HC.HDRip.XViD-juggs[ETRG].avi',
    'Dumb.and.Dumber.To.2014.HC.HDRip.XviD.AC3-EVO.avi'
];

$tvshows = [
    'anger.management.287.hdtv-lol.mp4',
    'ncis.1210.hdtv-lol.mp4',
    'once.upon.a.time.412.hdtv-lol.mp4',
    'person.of.interest.410.hdtv-lol.mp4',
];

$environmental = new Environmental(new Filesystem, '.env.php');
$tmdb = new Movies(new TheMovieDBServiceProvider($environmental));

foreach ($movies as $movie)
{
    $title = getSearchReadyFilename($movie);

    var_dump($tmdb->getMovieTitle($title));

}

function getSearchReadyFilename($filename)
{
    $value = preg_replace('(\\[.*?\\])', '', $filename);

    $words = preg_split('/[.]/', $value);

    $words = array_filter($words, function ($word)
    {
        return ! (preg_match("/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|2014|410/i", $word));
    });

    $output = join(' ', $words);

    var_dump($output);

    return $output;
}