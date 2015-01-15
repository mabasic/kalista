<?php

use Mabasic\Kalista\Movies\MovieCollectionInterface;

class MovieCollectionTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        Mockery::close();
    }

    public function collectionWithOneMovie()
    {
        $movie = Mockery::mock('Mabasic\Kalista\Movies\Movie');

        $collection = new MovieCollectionInterface;

        $collection->add($movie);

        return $collection;
    }

    /** @test */
    public function it_initializes_empty_collection()
    {
        $collection = new MovieCollectionInterface;

        $this->assertInstanceOf('Mabasic\Kalista\Movies\MovieCollection', $collection);

        $this->assertEquals(0, count($collection->getCollection()));
    }

    /** @test */
    public function it_initializes_collection_with_movies()
    {
        $movie = Mockery::mock('Mabasic\Kalista\Movies\Movie');

        $collection = new MovieCollectionInterface([$movie]);

        $this->assertEquals(1, count($collection->getCollection()));
    }

    /** @test */
    public function it_adds_movies_to_collection()
    {
        $movie = Mockery::mock('Mabasic\Kalista\Movies\Movie');

        $collection = new MovieCollectionInterface;

        $collection->add([$movie, $movie]);

        $this->assertEquals(2, count($collection->getCollection()));
    }

    /** @test */
    public function it_adds_a_movie_to_collection()
    {
        $collection = $this->collectionWithOneMovie();

        $this->assertEquals(1, count($collection->getCollection()));
    }

    /** @test */
    public function it_fetches_movie_names_from_database()
    {
        $movie = Mockery::mock('Mabasic\Kalista\Movies\Movie');
        $movie->shouldReceive('setName')->once();
        $movie->shouldReceive('getName')->once()
            ->andReturn('test');

        $database = Mockery::mock('Mabasic\Kalista\Databases\TheMovieDB\MovieDatabase');
        $database->shouldReceive('getName')->once()
            ->andReturn('test');

        $collection = new MovieCollectionInterface([$movie]);
        $collection->fetchMovieNames($database);

        $this->assertEquals('test', $collection->getCollection()[0]->getName());
    }

    /** @test */
    public function it_removes_movie_from_collection()
    {
        $collection = $this->collectionWithOneMovie();

        $collection->remove(0);

        $this->assertEquals(0, count($collection->getCollection()));
    }

    /** @test */
    public function it_gets_table_headers()
    {
        $collection = $this->collectionWithOneMovie();

        $this->assertInternalType('array', $collection->getHeaders());
    }

    /** @test */
    public function it_gets_table_rows()
    {
        $movie = Mockery::mock('Mabasic\Kalista\Movies\Movie');
        $movie->shouldReceive('getName')->times(2)
            ->andReturn('The Babadook');
        $movie->shouldReceive('file->getFilename')->times(2)
            ->andReturn('The.Babadook.2014.BRRip.XviD.AC3-EVO.avi');

        $collection = new MovieCollectionInterface([$movie]);

        $this->assertInternalType('array', $collection->getRows());

        $this->assertEquals(1, count($collection->getRows()));
    }

}
