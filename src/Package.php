<?php namespace Speelpenning\Workbench;

/**
 * This package class is used for simple DTO work.
 *
 * @package Speelpenning\Workbench
 */
class Package {

    /**
     * @var string
     */
    public $vendor;

    /**
     * @var string
     */
    public $package;

    /**
     * @var string
     */
    public $namespace;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $authorName;

    /**
     * @var string
     */
    public $authorEmail;

    /**
     * @var string
     */
    public $license;

    /**
     * Returns an array with object properties.
     *
     * @return array
     */
    public function getProperties()
    {
        return get_object_vars($this);
    }

    /**
     * Proposes the default namespace.
     *
     * @return string
     */
    public function proposeNamespace()
    {
        return $this->unslugAndCapitalize($this->vendor) . '\\' . $this->unslugAndCapitalize($this->package);
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
