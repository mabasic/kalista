<?php

use Mabasic\Kalista\TvShows\TvShowEpisodeCollectionInterface;

class TvShowEpisodeCollectionTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        Mockery::close();
    }

    public function collectionWithOneTvShowEpisode()
    {
        $tvShowEpisode = Mockery::mock('Mabasic\Kalista\TvShows\TvShowEpisode');

        $collection = new TvShowEpisodeCollectionInterface;

        $collection->add($tvShowEpisode);

        return $collection;
    }

    /** @test */
    public function it_initializes_empty_collection()
    {
        $collection = new TvShowEpisodeCollectionInterface;

        $this->assertInstanceOf('Mabasic\Kalista\TvShows\TvShowEpisodeCollection', $collection);

        $this->assertEquals(0, count($collection->getCollection()));
    }

    /** @test */
    public function it_initializes_collection_with_tvshowepisodes()
    {
        $tvShowEpisode = Mockery::mock('Mabasic\Kalista\TvShows\TvShowEpisode');

        $collection = new TvShowEpisodeCollectionInterface([$tvShowEpisode]);

        $this->assertEquals(1, count($collection->getCollection()));
    }

    /** @test */
    public function it_adds_tvshowepisodes_to_collection()
    {
        $tvShowEpisode = Mockery::mock('Mabasic\Kalista\TvShows\TvShowEpisode');

        $collection = new TvShowEpisodeCollectionInterface;

        $collection->add([$tvShowEpisode, $tvShowEpisode]);

        $this->assertEquals(2, count($collection->getCollection()));
    }

    /** @test */
    public function it_adds_a_tvshowepisode_to_collection()
    {
        $collection = $this->collectionWithOneTvShowEpisode();

        $this->assertEquals(1, count($collection->getCollection()));
    }

    /** @test */
    public function it_fetches_tvshowepisode_names_from_database()
    {
        $tvShowEpisode = Mockery::mock('Mabasic\Kalista\TvShows\TvShowEpisode');
        $tvShowEpisode->shouldReceive('setName')->once();
        $tvShowEpisode->shouldReceive('setShowName')->once();
        $tvShowEpisode->shouldReceive('getName')->once()
            ->andReturn('test');

        $database = Mockery::mock('Mabasic\Kalista\Databases\TheMovieDB\TvShowEpisodeDatabase');
        $database->shouldReceive('getName')->once()
            ->andReturn('test');
        $database->shouldReceive('getShowName')->once()
            ->andReturn('test');

        $collection = new TvShowEpisodeCollectionInterface([$tvShowEpisode]);
        $collection->fetchTvShowEpisodeInfo($database);

        $this->assertEquals('test', $collection->getCollection()[0]->getName());
    }

    /** @test */
    public function it_cannot_fetch_tvshowepisode_name_from_database()
    {
        $tvShowEpisode = Mockery::mock('Mabasic\Kalista\TvShows\TvShowEpisode');

        $database = Mockery::mock('Mabasic\Kalista\Databases\TheMovieDB\TvShowEpisodeDatabase');
        $database->shouldReceive('getName')->once()->andThrow('Mabasic\Kalista\Databases\Exceptions\TvShowEpisodeNotFoundException');

        $collection = new TvShowEpisodeCollectionInterface([$tvShowEpisode]);
        $collection->fetchTvShowEpisodeInfo($database);

        $this->assertEquals(0, count($collection->getCollection()));
    }

    /** @test */
    public function it_removes_tvshowepisode_from_collection()
    {
        $collection = $this->collectionWithOneTvShowEpisode();

        $collection->remove(0);

        $this->assertEquals(0, count($collection->getCollection()));
    }

    /** @test */
    public function it_gets_table_headers()
    {
        $collection = $this->collectionWithOneTvShowEpisode();

        $this->assertInternalType('array', $collection->getHeaders());
    }

    /** @test */
    public function it_gets_table_rows()
    {
        $tvShowEpisode = Mockery::mock('Mabasic\Kalista\TvShows\TvShowEpisode');
        $tvShowEpisode->shouldReceive('getName')->times(2)
            ->andReturn('The Babadook');
        $tvShowEpisode->shouldReceive('file->getFilename')->times(2)
            ->andReturn('The.Babadook.2014.BRRip.XviD.AC3-EVO.avi');
        $tvShowEpisode->shouldReceive('getEpisodeNumber')->times(2)
            ->andReturn('test');
        $tvShowEpisode->shouldReceive('getShowName')->times(2)
            ->andReturn('test');
        $tvShowEpisode->shouldReceive('getSeason')->times(2)
            ->andReturn('test');

        $collection = new TvShowEpisodeCollectionInterface([$tvShowEpisode]);

        $this->assertInternalType('array', $collection->getRows());

        $this->assertEquals(1, count($collection->getRows()));
    }

}
