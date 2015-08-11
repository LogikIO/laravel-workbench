<?php namespace Speelpenning\Workbench\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Factory;
use Speelpenning\Workbench\Exceptions\FileNotCreated;

class GitIgnoreGenerator {

    /**
     * @var Factory
     */
    protected $view;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * GitIgnoreGenerator constructor.
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
     * Creates a .gitignore file.
     *
     * @param string $path
     * @throws FileNotCreated
     */
    public function generate($path)
    {
        $file = $path . DIRECTORY_SEPARATOR . '.gitignore';

        $gitIgnore = $this->view->make('workbench::gitignore')->render();

        if ( ! $this->filesystem->put($file, $gitIgnore)) {
            throw new FileNotCreated($file);
        }
    }

}
