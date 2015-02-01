<?php  namespace Mabasic\Kalista\Traits;

trait SanitizerTrait {

    public function sanitizeText($text)
    {
        return preg_replace("/[:]/i", '', $text);
    }

}