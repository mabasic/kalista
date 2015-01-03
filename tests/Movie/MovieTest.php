<?php
use Mabasic\Kalista\Movies\Movie;

class MovieTest extends PHPUnit_Framework_TestCase {

    public $movie;

    public $file;

    public $cleaner;

    public function tearDown()
    {
        Mockery::close();
    }

    public function setUp()
    {
        $this->file = Mockery::mock('SplFileInfo');

        $this->cleaner = Mockery::mock('Mabasic\Kalista\Movies\MovieFilenameCleaner');

        $this->movie = new Movie($this->file, $this->cleaner);
    }

    /** @test */
    public function it_gets_the_filename_of_the_file()
    {
        $this->file->shouldReceive('getFilename')->once()
            ->andReturn('The.Babadook.2014.BRRip.XviD.AC3-EVO.avi');

        $filename = $this->movie->file()->getFilename();

        $this->assertEquals('The.Babadook.2014.BRRip.XviD.AC3-EVO.avi', $filename);
    }

    /** @test */
    public function it_sets_and_gets_the_name_of_the_movie()
    {
        $name = 'The Babadook';

        $this->movie->setName($name);

        $this->assertEquals($name, $this->movie->getName());
    }

    /** @test */
    public function it_cleans_the_filename()
    {
        $this->file->shouldReceive('getFilename')->once()
            ->andReturn('The.Babadook.2014.BRRip.XviD.AC3-EVO.avi');

        $this->cleaner->shouldReceive('clean')->once()
            ->andReturn('The Babadook');

        $cleanedFilename = $this->movie->getCleanedFilename();

        $this->assertEquals('The Babadook', $cleanedFilename);
    }

    /** @test */
    public function it_gets_filename()
    {
        $this->file
            ->shouldReceive('getExtension')->once()
            ->andReturn('avi');

        $name = 'The Babadook';
        $this->movie->setName($name);

        $filename = $this->movie->getFilename();

        $this->assertEquals('The Babadook.avi', $filename);
    }

    /** @test */
    public function it_gets_pathname()
    {
        $this->file
            ->shouldReceive('getExtension')->once()
            ->andReturn('avi')
            ->shouldReceive('getPath')->once()
            ->andReturn('C:\\test');

        $name = 'The Babadook';
        $this->movie->setName($name);

        $pathName = $this->movie->getPathname();

        $this->assertEquals('C:\\test\\The Babadook.avi', $pathName);
    }

}
