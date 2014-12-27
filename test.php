<?php

require __DIR__ . '/vendor/autoload.php';

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

include_once('.local.env.php');

//$token = new \Tmdb\ApiToken('replace_with_your_api_key');
$token = new \Tmdb\ApiToken($tmdb_api_key);
$client = new \Tmdb\Client($token);

foreach ($movies as $movie)
{
    $movie = getSearchReadyFilename($movie);

    searchTMDBForMovie($movie, $client);
}

foreach ($tvshows as $tvshow)
{
    $tvshow = getSearchReadyFilenameForTvShow($tvshow);

    searchTMDBForTVShow($tvshow, $client);
}

function searchTMDBForTVShow($file, \Tmdb\Client $client)
{
    $tvshow = $client->getSearchApi()->searchTv($file);

    $tvshow = $tvshow['results'][0];

    $repository = new \Tmdb\Repository\TvEpisodeRepository($client);

    $tvshow = $repository->load($tvshow['id'], 2, 87);

    var_dump($tvshow->getName());
}

function searchTMDBForMovie($file, \Tmdb\Client $client)
{
    $movie = $client->getSearchApi()->searchMovies($file);

    $movie = $movie['results'][0];

    $repository = new \Tmdb\Repository\MovieRepository($client);

    $movie = $repository->load($movie['id']);

    var_dump($movie->getTitle());
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

function getSearchReadyFilenameForTvShow($filename)
{
    $value = preg_replace('(\\[.*?\\])', '', $filename);

    $words = preg_split('/[.]/', $value);

    $words = array_filter($words, function ($word)
    {
        return ! (preg_match("/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|[0-9]/i", $word));
    });

    $output = join(' ', $words);

    var_dump($output);

    return $output;
}

/**
 * TODO:
 * set API KEY in environment using set, setx, export ...
 * implement TMDB for movies and tvshows
 * implement OMDB for movies without API KEY
 */
