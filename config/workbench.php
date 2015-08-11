<?php

/*
 * This file holds the configuration for the speelpenning/laravel-workbench package. The workbench works out-of-the-box,
 * but I recommend adding these options to your .env file for your convenience.
 */
return [

    /*
     * Vendor name
     *
     * If set, this will be used as your default vendor name when creating new packages.
     */
    'vendor' => strtolower(env('WORKBENCH_VENDOR')),

    /*
     * Author details
     *
     * Used as the default author name and e-mail address in composer.json and LICENSE.
     */
    'author' => [
        'name' => env('WORKBENCH_AUTHOR_NAME'),
        'email' => env('WORKBENCH_AUTHOR_EMAIL'),
    ],

    /*
     * License
     *
     * Configure the license you prefer to use here by default. It will be used in composer.json and for creating the
     * LICENSE file (if supported). When not configured, the MIT license will be used as a default.
     */
    'license' => env('WORKBENCH_LICENSE', 'MIT'),

    /*
     * Skip defaults
     *
     * When set to true, the workbench:package command will skip the questions that have a pre-configured value.
     */
    'skip_defaults' => (bool)env('WORKBENCH_SKIP_DEFAULTS', false),

    /*
     * Package directory
     *
     * This is the path in which workbench packages are located. It is relative to the Laravel base path and defaults
     * to 'packages'. This directory will be created automatically when installing this package.
     */
    'path' => base_path(env('WORKBENCH_PATH', 'packages')),

];
