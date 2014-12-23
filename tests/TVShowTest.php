<?php

use Mabasic\Kalista\TVShows\TVShow;

class TVShowTest extends PHPUnit_Framework_TestCase {

    protected $tvshow;

    public function setUp()
    {
        $this->tvshow = new TvShow('Anger Management - 2x88 - Charlie Gets Tied Up with a Catholic Girl', 'C:\Users\Mario\Torrents\Anger.Management.S02E88.HDTV.x264-LOL[ettv]');
    }

    /** @test */
    public function it_gets_destination_path()
    {
        $destinationPath = $this->tvshow->getDestinationPath('C:\Users\Mario\Torrents');

        $this->assertEquals('C:\Users\Mario\Torrents/Anger Management/Season 2', $destinationPath);
    }

    /** @test
    public function it_detects_information()
    {
        $result = $this->tvshow->detectInformation();

        $this->assertTrue($result);
    }*/

}
