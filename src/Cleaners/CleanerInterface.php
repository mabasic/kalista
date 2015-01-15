<?php  namespace Mabasic\Kalista\Cleaners;

interface CleanerInterface {

    public function clean($string);

    public function prepare($filename, $regex);

}