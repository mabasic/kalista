<?php
use Mabasic\Kalista\File;

class FileTest extends PHPUnit_Framework_TestCase {

    protected $file;

    public function setUp()
    {
        $this->file = new File('Horrible Bosses 2 [2014, R, 7.0].avi', 'C:\Users\Mario\Torrents\Horrible Bosses 2 (2014) HDRip HC XViD AC3-RAV3N');
    }

    /** @test */
    public function it_gets_full_path()
    {
        $fullPath = $this->file->getFullPath();

        $this->assertEquals('C:\Users\Mario\Torrents\Horrible Bosses 2 (2014) HDRip HC XViD AC3-RAV3N/Horrible Bosses 2 [2014, R, 7.0].avi', $fullPath);
    }

    /** @test */
    public function it_gets_the_file_extension()
    {
        $extension = $this->file->getExtension();

        $this->assertEquals('avi', $extension);
    }
}
