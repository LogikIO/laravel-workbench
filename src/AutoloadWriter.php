<?php namespace Speelpenning\Workbench;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

class AutoloadWriter {

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $file;

    /**
     * AutoloadWriter constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->file = base_path('composer.json');
    }

    /**
     * Adds a namespace with source path to the autoload section.
     *
     * @param string $sourcePath
     * @param string $namespace
     */
    public function writeAutoloadPsr4($sourcePath, $namespace)
    {
        $array = $this->getComposerDotJsonContents();

        $updated = array_add(
            $array,
            'autoload.psr-4.' . $namespace . '\\',
            $this->createRelativeSourcePath($sourcePath)
        );

        $this->putComposerDotJsonContents($updated);
    }

    /**
     * Removes a namespace from the autoload section.
     *
     * @param string $namespace
     */
    public function removeAutoloadPsr4($namespace)
    {
        $array = $this->getComposerDotJsonContents();

        array_forget(
            $array,
            'autoload.psr-4.' . $namespace . '\\'
        );

        $this->putComposerDotJsonContents($array);
    }

    /**
     * Returns an associative array with the composer.json contents
     *
     * @return array
     * @throws FileNotFoundException
     */
    protected function getComposerDotJsonContents()
    {
        return json_decode(
            $this->filesystem->get($this->file),
            true
        );
    }

    /**
     * Writes the new composer.json file.
     *
     * @param array $contents
     * @return int
     */
    protected function putComposerDotJsonContents(array $contents)
    {
        return $this->filesystem->put(
            $this->file,
            json_encode($contents, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)
        );
    }

    /**
     * Creates the relative source path for autoloading.
     *
     * @param string $sourcePath
     * @return string
     */
    protected function createRelativeSourcePath($sourcePath)
    {
        return str_replace(base_path() . DIRECTORY_SEPARATOR, '', $sourcePath) . '/';
    }

}