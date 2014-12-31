<?php

use Mabasic\Kalista\TvShows\TvShowEpisodeFilenameCleaner;

class TvShowEpisodeFilenameCleanerTest extends PHPUnit_Framework_TestCase {

    public $cleaner;

    public function setUp()
    {
        $this->cleaner = new TvShowEpisodeFilenameCleaner;
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function it_cannot_clean_the_filename()
    {
        $filename = "The Mentalist - 7x3 - Orange Blossom Ice Cream.mp4";

        $result = $this->cleaner->clean($filename);

        $this->assertEquals('', $result);
    }

    /** @test */
    public function it_cleans_the_filename()
    {
        $filename = "the.big.bang.theory.811.hdtv-lol.mp4";

        $result = $this->cleaner->clean($filename);

        $this->assertEquals('the big bang theory', $result);


        $filename = "NCIS.Los.Angeles.S06E11.HDTV.x264-LOL2.mp4";

        $result = $this->cleaner->clean($filename);

        $this->assertEquals('NCIS Los Angeles', $result);
    }

    /** @test */
    public function it_gets_only_season_and_episode_numbers()
    {
        $filename = "the.big.bang.theory.811.hdtv-lol.mp4";

        $result = $this->cleaner->getOnlySeasonAndEpisodeNumbers($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|X264/i");

        $this->assertEquals('811', $result);


        $filename = "NCIS.Los.Angeles.S06E11.HDTV.x264-LOL2.mp4";

        $result = $this->cleaner->getOnlySeasonAndEpisodeNumbers($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|X264/i");

        $this->assertEquals('611', $result);

        $filename = "The Mentalist - 7x3 - Orange Blossom Ice Cream.mp4";

        $result = $this->cleaner->getOnlySeasonAndEpisodeNumbers($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|X264/i");

        $this->assertEquals('73', $result);
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function it_cannot_get_season_and_episode_numbers()
    {
        $filename = "The Mentalist - Orange Blossom Ice Cream.mp4";

        $result = $this->cleaner->getOnlySeasonAndEpisodeNumbers($filename, "/HDTV|MP4|AVI|HC|HDRIP|XVID|AC3|X264/i");

        $this->assertEquals('73', $result);
    }

}
