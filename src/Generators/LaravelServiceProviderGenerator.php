<?php namespace Speelpenning\Workbench\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Factory;
use Speelpenning\Workbench\Exceptions\FileNotCreated;

class LaravelServiceProviderGenerator {

    /**
     * @var Factory
     */
    protected $view;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * LaravelServiceProviderGenerator constructor.
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
     * Generates a Laravel service provider.
     *
     * @param string $path
     * @param string $namespace
     * @throws FileNotCreated
     */
    public function generate($path, $namespace)
    {
        $file = $path . DIRECTORY_SEPARATOR . $this->extractClassPrefix($namespace) . 'ServiceProvider.php';

        $code = $this->view->make('workbench::service-provider', [
            'namespace' => $namespace,
            'classPrefix' => $this->extractClassPrefix($namespace),
        ])->render();

        if ( ! $this->filesystem->put($file, $code)) {
            throw new FileNotCreated($file);
        }
    }

    /**
     * Extracts the class prefix from the namespace.
     *
     * @param string $namespace
     * @return string
     */
    public function extractClassPrefix($namespace)
    {
        return last(explode('\\', $namespace));
    }

}
