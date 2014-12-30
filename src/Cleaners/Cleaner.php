<?php  namespace Mabasic\Kalista\Cleaners;

interface Cleaner {

    public function clean($string);

    public function prepare($filename, $regex);

}