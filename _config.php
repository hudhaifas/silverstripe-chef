<?php

/**
 * Fetches the name of the current module folder name.
 *
 * @return string
 */
if (!defined('RESTAURANT_DIR')) {
    define('RESTAURANT_DIR', ltrim(Director::makeRelative(realpath(__DIR__)), DIRECTORY_SEPARATOR));
}

//Display in cms menu
ChefAdmin::add_extension('SubsiteMenuExtension');