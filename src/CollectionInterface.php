<?php namespace Mabasic\Kalista;

interface CollectionInterface {

    public function add($movies);

    public function getCollection();

    public function getUnresolved();

    public function sendPendingToUnresolved();

    public function addToUnresolved(VideoFileInterface $videoFile);

    public function addToPending($filename);

    public function getHeaders();

    public function getRows();

    public function remove($index);

    public function getUnresolvedHeaders();

    public function getUnresolvedRows();

}