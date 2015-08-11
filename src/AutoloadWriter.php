<?php namespace Speelpenning\Workbench;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

class AutoloadWriter {

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * AutoloadWriter constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $sourcePath
     * @param string $namespace
     * @throws FileNotFoundException
     */
    public function writeAutoloadPsr4($sourcePath, $namespace)
    {
        $file = base_path('composer.json');
        $relativeSourcePath = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $sourcePath) . '/';

        $json = $this->filesystem->get($file);

        $array = json_decode($json, true);

        $updated = array_add($array, 'autoload.psr-4.' . $namespace . '\\', $relativeSourcePath);

        $json = json_encode($updated, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);

        $this->filesystem->put($file, $json);
    }

}