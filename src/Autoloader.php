<?php namespace Speelpenning\Workbench;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * The Autoloader is used to load all autoload.php files from the packages that are developed in the workbench.
 */
class Autoloader {

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * Autoloader constructor.
     *
     * @param Finder $finder
     * @param Filesystem $filesystem
     * @param Repository $config
     */
    public function __construct(Finder $finder, Filesystem $filesystem, Repository $config)
    {
        $this->finder = $finder;
        $this->filesystem = $filesystem;
        $this->config = $config;

        $this->createPackagePathIfNecessary();
    }

    /**
     * Loads all autoload.php files.
     *
     * @return int
     */
    public function load()
    {
        $files = $this->findAutoloadFiles();
        foreach ($files as $file) {
            $this->filesystem->requireOnce($file);
        }
       return $files->count();
    }

    /**
     * Returns the path to the package directory.
     *
     * @return string
     */
    protected function getPathToPackages()
    {
        return $this->config->get('workbench.path');
    }

    /**
     * Creates the package path if it does not exist.
     */
    protected function createPackagePathIfNecessary()
    {
        $path = $this->getPathToPackages();

        if ( ! $this->filesystem->exists($path)) {
            $this->filesystem->makeDirectory($path, 0755, true);
        }
    }

    /**
     * Returns all autoload.php file paths from the package directory.
     *
     * @return Finder
     */
    protected function findAutoloadFiles()
    {
        return $this->finder->in($this->getPathToPackages())->files()->name('autoload.php')->depth('<=3')->followLinks();
    }

}