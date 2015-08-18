<?php namespace Speelpenning\Workbench\Generators;

use Speelpenning\Workbench\Package;

class NamespaceGenerator {

    /**
     * It generates a namespace based on the package properties.
     *
     * @param Package $package
     * @return string
     */
    public function generate(Package $package)
    {
        return rtrim($this->unslugAndCapitalize($package->vendor) . '\\' . $this->unslugAndCapitalize($package->package, '\\'));
    }

    /**
     * Unslugs the vendor and package name.
     *
     * @param string $string
     * @return string
     */
    protected function unslugAndCapitalize($string)
    {
        // Turn all dashes into spaces to create words.
        $string = str_replace('-', ' ', $string);

        // Capitalize all words.
        $string = ucwords($string);

        // Remove all spaces.
        return str_replace(' ', '', $string);
    }

}
