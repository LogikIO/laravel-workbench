<?php namespace Speelpenning\Workbench\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Factory;
use Speelpenning\Workbench\Exceptions\FileNotCreated;
use Speelpenning\Workbench\Exceptions\LicenseNotSupported;

class LicenseFileGenerator {

    /**
     * @var Factory
     */
    protected $view;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * LicenseFileGenerator constructor.
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
     * Generates the license file.
     *
     * @param string $path
     * @param string $license
     * @param string $vendor
     * @param string $package
     * @param string $description
     * @param string $authorName
     * @param string $authorEmail
     * @throws LicenseNotSupported
     * @throws FileNotCreated
     */
    public function generate($path, $license, $vendor, $package, $description, $authorName, $authorEmail)
    {
        if ( ! in_array($license, $this->getSupportedLicenses())) {
            throw new LicenseNotSupported($license);
        }

        $file = $path . DIRECTORY_SEPARATOR . 'LICENSE';

        $license = $this->view->make(
            'workbench::licenses.' . strtolower(str_slug($license)),
            compact('vendor', 'package', 'description', 'authorName', 'authorEmail'),
            ['year' => date('Y')]
        )->render();

        if ( ! $this->filesystem->put($file, $license)) {
            throw new FileNotCreated($file);
        }
    }

    /**
     * Returns an array with supported licenses.
     *
     * @return array
     */
    public function getSupportedLicenses()
    {
        return [
            'Apache-2.0',
            'GPL-2.0',
            'GPL-3.0',
            'LGPL-2.1',
            'MIT',
        ];
    }

}
