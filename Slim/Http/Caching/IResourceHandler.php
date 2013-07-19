<?php
/**
 * Slim - Caching Manager
 *
 * @author      Magnus Buk <MagnusBuk@gmx.de>
 * @copyright   2013 Magnus Buk
 * @link        https://github.com/maegnes/SlimCachingManager
 */
namespace Slim\Http\Caching;

/**
 * Slim IResourceManager
 *
 * ResourceManager helps you to store and read data of cached resources
 *
 * @package Slim\Http\Caching
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
interface IResourceHandler {

    public function write( $resource, $lifetime );

    public function read( $resource );

    public function gc();

}