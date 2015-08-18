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

}
