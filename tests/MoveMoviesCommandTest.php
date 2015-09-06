<?php

use Mabasic\Kalista\MoveMoviesCommand;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class MoveMoviesCommandTest extends PHPUnit_Framework_TestCase
{
    public $filesystem;

    public function setUp()
    {
        $this->filesystem = Mockery::mock('Illuminate\Filesystem\Filesystem');
        $this->filesystem->shouldReceive('allFiles')->once()
            ->andReturn([
                new SplFileInfo('A Haunted House [2013, R, 5.4].avi', null, null),
                new SplFileInfo('A Thousand Words [2012, PG-13, 5.6].avi', null, null)
            ]);
        $this->filesystem->shouldReceive('makeDirectory')->times(2);
        $this->filesystem->shouldReceive('move')->times(2);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    public function it_moves_movies_from_source_to_destionation()
    {
        $application = new Application();
        $application->add(new MoveMoviesCommand($this->filesystem));

        $command = $application->find('move:movies');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'source' => 'source-directory',
            'destination' => 'destination-directory'
        ]);

        $this->assertRegExp('/Movies have been moved!/', $commandTester->getDisplay());
    }
}
