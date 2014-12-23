<?php
use Mabasic\Kalista\Movies\Movie;

class MovieTest extends PHPUnit_Framework_TestCase {

    protected $movie;

    public function setUp()
    {
        $this->movie = new Movie('Horrible Bosses 2 [2014, R, 7.0].avi', 'C:\Users\Mario\Torrents\Horrible Bosses 2 (2014) HDRip HC XViD AC3-RAV3N');
    }

    /** @test */
    public function it_detects_movie_name_from_filename()
    {
        $movieName = $this->movie->detectMovieName();

        $this->assertEquals('Horrible Bosses 2 ', $movieName);
    }

    /** @test */
    public function it_does_not_detect_movie_name_from_filename()
    {
        $this->movie = new Movie('Horrible Bosses 2.avi', 'C:\Users\Mario\Torrents\Horrible Bosses 2 (2014) HDRip HC XViD AC3-RAV3N');

        $movieName = $this->movie->detectMovieName();

        $this->assertEquals('Horrible Bosses 2', $movieName);
    }

    /** @test */
    public function it_gets_destination_path()
    {
        $destinationPath = $this->movie->getDestinationPath('C:\Users\Mario');

        $this->assertEquals('C:\Users\Mario/Horrible Bosses 2 ', $destinationPath);
    }
}
