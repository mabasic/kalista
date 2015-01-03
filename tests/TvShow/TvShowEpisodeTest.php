<?php

use Mabasic\Kalista\TvShows\TvShowEpisode;

class TvShowEpisodeTest extends PHPUnit_Framework_TestCase {

    public $tvShowEpisode;

    public $file;

    public $cleaner;

    public function tearDown()
    {
        Mockery::close();
    }

    public function setUp()
    {
        $this->file = Mockery::mock('SplFileInfo');

        $this->cleaner = Mockery::mock('Mabasic\Kalista\TvShows\TvShowEpisodeFilenameCleaner');

        $this->tvShowEpisode = new TvShowEpisode($this->file, $this->cleaner);
    }

    /** @test */
    public function it_gets_the_filename_of_the_file()
    {
        $this->file->shouldReceive('getFilename')->once()
            ->andReturn('the.big.bang.theory.811.hdtv-lol.mp4');

        $filename = $this->tvShowEpisode->file()->getFilename();

        $this->assertEquals('the.big.bang.theory.811.hdtv-lol.mp4', $filename);
    }

    /** @test */
    public function it_sets_and_gets_the_name_of_the_episode()
    {
        $name = 'The Clean Room Infiltration';

        $this->tvShowEpisode->setName($name);

        $this->assertEquals($name, $this->tvShowEpisode->getName());
    }

    /** @test */
    public function it_sets_and_gets_the_name_of_the_show()
    {
        $showName = 'The Big Bang Theory';

        $this->tvShowEpisode->setShowName($showName);

        $this->assertEquals($showName, $this->tvShowEpisode->getShowName());
    }

    /** @test */
    public function it_cleans_the_filename()
    {
        $this->file->shouldReceive('getFilename')->once()
            ->andReturn('the.big.bang.theory.811.hdtv-lol.mp4');

        $this->cleaner->shouldReceive('clean')->once()
            ->andReturn('the big bang theory');

        $cleanedFilename = $this->tvShowEpisode->getCleanedFilename();

        $this->assertEquals('the big bang theory', $cleanedFilename);
    }

    /** @test */
    public function it_gets_season_and_episode_number()
    {
        $this->file->shouldReceive('getFilename')->once()
            ->andReturn('the.big.bang.theory.811.hdtv-lol.mp4');

        $this->cleaner->shouldReceive('prepare')->once()
            ->andReturn('811');

        $seasonAndEpisodeNumber = $this->tvShowEpisode->getCleanedFilenameWithNumbers();

        $this->assertEquals('811', $seasonAndEpisodeNumber);
    }

    /** @test */
    public function it_gets_season_number()
    {
        $this->file->shouldReceive('getFilename')->once()
            ->andReturn('the.big.bang.theory.811.hdtv-lol.mp4');

        $this->cleaner->shouldReceive('prepare')->once()
            ->andReturn('811');

        $season = $this->tvShowEpisode->getSeason();

        $this->assertEquals(8, $season);
    }

    /** @test */
    public function it_gets_episode_number()
    {
        $this->file->shouldReceive('getFilename')->once()
            ->andReturn('the.big.bang.theory.811.hdtv-lol.mp4');

        $this->cleaner->shouldReceive('prepare')->once()
            ->andReturn('811');

        $season = $this->tvShowEpisode->getEpisodeNumber();

        $this->assertEquals(11, $season);
    }

    /** @test */
    public function it_gets_filename()
    {
        $this->file
            ->shouldReceive('getFilename')->times(2)
            ->andReturn('the.big.bang.theory.811.hdtv-lol.mp4')
            ->shouldReceive('getExtension')->once()
            ->andReturn('mp4');

        $this->cleaner->shouldReceive('prepare')->times(2)
            ->andReturn('811');

        $name = 'The Clean Room Infiltration';
        $this->tvShowEpisode->setName($name);

        $showName = 'The Big Bang Theory';
        $this->tvShowEpisode->setShowName($showName);

        $filename = $this->tvShowEpisode->getFilename();

        $this->assertEquals('The Big Bang Theory - 8x11 - The Clean Room Infiltration.mp4', $filename);
    }

    /** @test */
    public function it_gets_pathname()
    {
        $this->file
            ->shouldReceive('getFilename')->times(2)
            ->andReturn('the.big.bang.theory.811.hdtv-lol.mp4')
            ->shouldReceive('getExtension')->once()
            ->andReturn('mp4')
            ->shouldReceive('getPath')->once()
            ->andReturn('C:\\test');

        $this->cleaner->shouldReceive('prepare')->times(2)
            ->andReturn('811');

        $name = 'The Clean Room Infiltration';
        $this->tvShowEpisode->setName($name);

        $showName = 'The Big Bang Theory';
        $this->tvShowEpisode->setShowName($showName);

        $pathName = $this->tvShowEpisode->getPathname();

        $this->assertEquals('C:\\test\\The Big Bang Theory - 8x11 - The Clean Room Infiltration.mp4', $pathName);
    }

}
