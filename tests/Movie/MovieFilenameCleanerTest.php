<?php

use Mabasic\Kalista\Movies\MovieFilenameCleaner;

class MovieFilenameCleanerTest extends PHPUnit_Framework_TestCase {

    public $cleaner;

    public function setUp()
    {
        $this->cleaner = new MovieFilenameCleaner;
    }

    /** @test */
    public function it_cleans_the_filename()
    {
        $filename = "The Equalizer [2014, R, 7.7].avi";

        $result = $this->cleaner->clean($filename);

        $this->assertEquals('The Equalizer ', $result);


        $filename = "The.Book.of.Life.2014.HDRip.XviD.AC3-EVO.avi";

        $result = $this->cleaner->clean($filename);

        $this->assertEquals('The Book of Life', $result);


        $filename = "Sample.avi";

        $result = $this->cleaner->clean($filename);

        $this->assertEquals('Sample', $result);
    }

}
