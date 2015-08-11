<?php namespace Speelpenning\Workbench\Generators;

use Illuminate\Filesystem\Filesystem;
use Speelpenning\Workbench\Exceptions\DirectoryNotCreated;
use Speelpenning\Workbench\Exceptions\PackageExists;

class PackageGenerator {

    /**
     * @var Filesystem
     */
    protected $filesystem;


    protected $packagePath;

    protected $sourcePath;

    /**
     * PackageGenerator constructor.
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Generates the package directory structure.
     *
     * @param string $packagePath
     * @param string $vendor
     * @param string $package
     * @throws DirectoryNotCreated
     * @throws PackageExists
     */
    public function generate($packagePath, $vendor, $package)
    {
        $this->createPackageDirectory($packagePath, $vendor, $package);
        $this->createSourceDirectory();
    }

    /**
     * Creates the package directory and returns the path.
     *
     * @param string $path
     * @param string $vendor
     * @param string $package
     * @throws DirectoryNotCreated
     * @throws PackageExists
     */
    protected function createPackageDirectory($path, $vendor, $package)
    {
        $directory = implode(DIRECTORY_SEPARATOR, [$path, $vendor, $package]);

        if ($this->filesystem->exists($directory)) {
            throw new PackageExists($vendor . '/' . $package);
        }

        if ( ! $this->filesystem->makeDirectory($directory, 0755, true)) {
            throw new DirectoryNotCreated($directory);
        }

        $this->packagePath = $directory;
    }

    /**
     * Creates the source directory and returns the path.
     *
     * @throws DirectoryNotCreated
     */
    protected function createSourceDirectory()
    {
        $directory = $this->packagePath . DIRECTORY_SEPARATOR . 'src';

        if ( ! $this->filesystem->makeDirectory($directory, 0755, true)) {
            throw new DirectoryNotCreated($directory);
        }

        $this->sourcePath = $directory;
    }

    /**
     * Returns the package path.
     *
     * @return string
     */
    public function getPackagePath()
    {
        return $this->packagePath;
    }

    /**
     * Returns the source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->sourcePath;
    }

}
