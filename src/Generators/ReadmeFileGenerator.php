<?php namespace Speelpenning\Workbench\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Factory;
use Speelpenning\Workbench\Exceptions\FileNotCreated;

class ReadmeFileGenerator {

    /**
     * @var Factory
     */
    protected $view;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * ReadmeFileGenerator constructor.
     *
     * @param Factory $view
     * @param Filesystem $filesystem
     */
    public function __construct(Factory $view, Filesystem $filesystem)
    {
        $this->view = $view;
        $this->filesystem = $filesystem;
    }

    /**
     * Generates the README.md file.
     *
     * @param string $path
     * @param string $package
     * @param string $description
     * @throws FileNotCreated
     */
    public function generate($path, $package, $description)
    {
        $file = $path . DIRECTORY_SEPARATOR . 'README.md';

        $readme = $this->view->make('workbench::readme', compact('package', 'description'))->render();

        if ( ! $this->filesystem->put($file, $readme)) {
            throw new FileNotCreated($file);
        }
    }

}
