<?php
/**
 * Use to register catalog graphql module
 * php version 8.1.11
 *
 * @category FS
 * @package  FS_SiteConfig
 * @author   FS <farooqh@fs.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU / GPLv3
 * @link     https://fs.org/tnc.html 	
 */

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'FS_SiteConfig',
    __DIR__
);