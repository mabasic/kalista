<?php

use Mabasic\Kalista\Movies\MovieCollection;

class MovieCollectionTest extends PHPUnit_Framework_TestCase {

    public function tearDown()
    {
        Mockery::close();
    }

    /** @test */
    public function it_initializes_empty_collection()
    {
        $collection = new MovieCollection;

        $this->assertInstanceOf('Mabasic\Kalista\Movies\MovieCollection', $collection);

        $this->assertEquals(0, count($collection->getCollection()));
    }

    /** @test */
    public function it_initializes_collection_with_movies()
    {
        $movie = Mockery::mock('Mabasic\Kalista\Movies\Movie');

        $collection = new MovieCollection([$movie]);

        $this->assertEquals(1, count($collection->getCollection()));
    }

    /** @test */
    public function it_adds_movies_to_collection()
    {
        $movie = Mockery::mock('Mabasic\Kalista\Movies\Movie');

        $collection = new MovieCollection;

        $collection->add([$movie, $movie]);

        $this->assertEquals(2, count($collection->getCollection()));
    }

    /** @test */
    public function it_adds_a_movie_to_collection()
    {
        $movie = Mockery::mock('Mabasic\Kalista\Movies\Movie');

        $collection = new MovieCollection;

        $collection->add($movie);

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

        $collection = new MovieCollection([$movie]);
        $collection->fetchMovieNames($database);

        $this->assertEquals('test', $collection->getCollection()[0]->getName());

    }

}
