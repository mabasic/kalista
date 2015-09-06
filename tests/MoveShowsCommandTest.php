<?php

use Mabasic\Kalista\MoveShowsCommand;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class MoveShowsCommandTest extends PHPUnit_Framework_TestCase
{
    public $filesystem;

    public function setUp()
    {
        $this->filesystem = Mockery::mock('Illuminate\Filesystem\Filesystem');
        $this->filesystem->shouldReceive('allFiles')->once()
            ->andReturn([
                new SplFileInfo('Defiance - 3x03 - Broken Bough.mp4', null, null),
                new SplFileInfo('Defiance - 3x11 - Of a Demon in My View.mp4', null, null)
            ]);
        $this->filesystem->shouldReceive('makeDirectory')->times(2);
        $this->filesystem->shouldReceive('move')->times(2);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    public function it_moves_shows_from_source_to_destionation()
    {
        $application = new Application();
        $application->add(new MoveShowsCommand($this->filesystem));

        $command = $application->find('move:shows');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'source' => 'source-directory',
            'destination' => 'destination-directory'
        ]);

        $this->assertRegExp('/Shows have been moved!/', $commandTester->getDisplay());
    }
}
