<?php
/**
 * Slim - Caching Manager
 *
 * @author      Magnus Buk <MagnusBuk@gmx.de>
 * @copyright   2013 Magnus Buk
 * @link        https://github.com/maegnes/SlimCachingManager
 */
namespace Slim\Http\Caching;
use Slim;

/**
 * Slim IResourceManager
 *
 * Interface for all ResourceMappers
 *
 * @package Slim\Http\Caching
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
interface IResourceMapper {

    public function setApplication( Slim\Slim $app );

    public function getApplication();

    public function setHandler( IResourceHandler $handler );

    public function getHandler();

    public function setHeaders();

}