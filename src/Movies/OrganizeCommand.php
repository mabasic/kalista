<?php namespace Mabasic\Kalista\Movies;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OrganizeCommand extends Command {

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('organize:movies')
            ->addArgument(
                'source',
                InputArgument::REQUIRED,
                'Source folder that needs to be organized.'
            )
            ->addArgument(
                'destination',
                InputArgument::REQUIRED,
                'Destination folder where organized files are stored.'
            )
            ->setDescription('Movies movies from one folder in another folder in separate folders.');
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $input->getArgument('source');
        $destination = $input->getArgument('destination');

        $this->organizeMovies($source, $destination, $output);
    }

    private function organizeMovies($source, $destination, OutputInterface $output)
    {
        $items = $this->scanDirectory($source);

        foreach ($items as $item)
        {
            if (is_dir($source . '/' . $item))
            {
                $this->organizeMovies($source . '/' . $item, $destination, $output);

                continue;
            }

            $target = $this->generateDirectoryPath($destination, $item);

            $this->createDirectory($target);

            $this->copyFileToDestination($source, $item, $target);

            $output->writeln($item);
        }
    }

    /**
     * @param $destination
     * @param $item
     * @return mixed
     */
    public function generateDirectoryPath($destination, $item)
    {
        // If filename is already formatted
        // return file name
        $output = explode('[', $item)[0];

        // If filename is not formatted
        if($item == $output)
        {
            // Return file name without extension
            $output = explode('.', $item)[0];
        }

        return $destination . '/' . $output;
    }

    /**
     * @param $source
     * @return array
     */
    public function scanDirectory($source)
    {
        return array_diff(scandir($source), array('..', '.'));
    }

    /**
     * @param $source
     * @param $item
     * @param $destination
     */
    private function copyFileToDestination($source, $item, $destination)
    {
        copy($source . '/' . $item, $destination . '/' . $item);
    }

    /**
     * @param $directoryPath
     */
    private function createDirectory($directoryPath)
    {
        if ( ! is_dir($directoryPath))
        {
            mkdir($directoryPath);
        }
    }
}