<?php namespace Mabasic\Kalista\Services\Environmental;

use Exception;
use Illuminate\Filesystem\Filesystem;

class Environmental {

    protected $filesystem;

    protected $environmentFile;

    public function __construct(Filesystem $filesystem, $environmentFile = '../../../.env.php')
    {
        $this->filesystem = $filesystem;
        $this->environmentFile = $environmentFile;

        $this->loadFromFile();
    }

    public function setEnvironmentVariable($name, $value)
    {
        putenv("{$name}={$value}");
        $_ENV[ $name ] = $value;
        $_SERVER[ $name ] = $value;
    }

    public function setEnvironmentVariables($variables)
    {
        foreach($variables as $key => $value)
        {
            $this->setEnvironmentVariable($key, $value);
        }
    }

    public function getEnvironmentVariable($name)
    {
        return getenv($name);
    }

    public function loadFromFile()
    {

        if ( ! $this->filesystem->exists($this->environmentFile))
        {
            throw new Exception('.env.php file not found!');
            //return [];
        }

        $variables = array_dot($this->filesystem->getRequire($this->environmentFile));

        $this->setEnvironmentVariables($variables);
    }

}