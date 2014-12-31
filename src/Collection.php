<?php namespace Mabasic\Kalista;

interface Collection {

    public function add($movies);

    public function getCollection();

    public function getHeaders();

    public function getRows();

    public function remove($index);

}