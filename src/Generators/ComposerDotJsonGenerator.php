<?php namespace Speelpenning\Workbench\Generators;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Filesystem\Filesystem;
use Speelpenning\Workbench\Exceptions\FileNotCreated;

class ComposerDotJsonGenerator {

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * ComposerDotJsonGenerator constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Generates the composer.json file.
     *
     * @param string $path
     * @param string $vendor
     * @param string $package
     * @param string $description
     * @param string $license
     * @param string $author_name
     * @param string $author_email
     * @param string $namespace
     * @throws FileNotCreated
     */
    public function generate($path, $vendor, $package, $description, $license, $author_name, $author_email, $namespace)
    {
        $file = $path . DIRECTORY_SEPARATOR . 'composer.json';

        $json = $this->toJson($vendor, $package, $description, $license, $author_name, $author_email, $namespace);

        if ( ! $this->filesystem->put($file, $json)) {
            throw new FileNotCreated($file);
        }
    }

    /**
     * Creates the JSON output.
     *
     * @param string $vendor
     * @param string $package
     * @param string $description
     * @param string $license
     * @param string $author_name
     * @param string $author_email
     * @param string $namespace
     * @return string
     */
    protected function toJson($vendor, $package, $description, $license, $author_name, $author_email, $namespace)
    {
        return json_encode([
            'name' => $vendor . DIRECTORY_SEPARATOR . $package,
            'description' => $description,
            'license' => $license,
            'authors' => [
                ['name' => $author_name, 'email' => $author_email]
            ],
            'autoload' => [
                'psr-4' => [
                    $namespace . '\\' => 'src/'
                ]
            ]
        ],
        JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    }

}