<?php namespace Mabasic\Kalista\TVShows\Exceptions;

class UnreadableTVShowInformationException extends \Exception {

    /**
     * @param string $message
     */
    function __construct($message)
    {
        parent::__construct($message);
    }

}