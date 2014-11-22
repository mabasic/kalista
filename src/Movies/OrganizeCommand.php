<?php namespace Mabasic\Kalista\Movies;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class OrganizeCommand extends Command {

    /**
     * @param $source
     * @return array
     */
    public function scanDirectory($source)
    {
        return array_diff(scandir($source), array('..', '.'));
    }

    /**
     * @param $item
     * @return mixed
     */
    public function getDirectoryNameFromFile($item)
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

        return $output;
    }

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
                // TODO: Do recursive

                //continue;
                $this->organizeMovies($source . '/' . $item, $destination, $output);

                continue;
            }

            $directoryName = $this->getDirectoryNameFromFile($item);

            $directoryPath = $destination . '/' . $directoryName;

            if ( ! is_dir($directoryPath))
            {
                mkdir($directoryPath);
            }

            copy($source . '/' . $item, $directoryPath . '/' . $item);

            $output->writeln($item);
        }
    }
}