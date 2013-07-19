<?php
/**
 * Slim - Caching Manager
 *
 * @author      Magnus Buk <MagnusBuk@gmx.de>
 * @copyright   2013 Magnus Buk
 * @link        https://github.com/maegnes/SlimCachingManager
 */
namespace Slim\Http\Caching;
use Slim\Slim, Exception;

/**
 * Slim ResourceMapper
 *
 * ResourceMapper checks if current resource has caching entries and/or adds them to selected data adapter (via IResourceHandler)
 *
 * @package Slim\Http\Caching
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class ResourceMapper implements IResourceMapper {

    /**
     * @var Slim
     */
    protected $_app = null;

    /**
     * @var IResourceHandler
     */
    protected $_handler = null;

    /**
     * Sets the current Slim application instance
     * @param Slim $app
     */
    public function setApplication( Slim $app ) {
        $this->_app = $app;
    }

    /**
     * Returns the current Slim application instance
     *
     * @access public
     * @return Slim
     */
    public function getApplication() {
        return $this->_app;
    }

    /**
     * Set the resource handler
     *
     * @access public
     * @param IResourceHandler $handler
     * @return void
     */
    public function setHandler( IResourceHandler $handler ) {
        $this->_handler = $handler;
    }

    /**
     * Returns the resource handler
     *
     * @access public
     * @throws Exception
     * @return IResourceHandler
     */
    public function getHandler() {
        if( !$this->_handler instanceof IResourceHandler )
            throw new Exception( 'No valid ResourceHandler was set!' );
        return $this->_handler;
    }

    /**
     * Method checks if current resource should or is being cached. if yes ETag and expire date are being set
     *
     * @access public
     * @return void
     */
    public function setHeaders() {


    }

    /**
     * @param null $resource
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function isResourceCached( $resource = null ) {
        if( is_null( $resource ) )
            throw new \InvalidArgumentException( 'invalid resource given' );
        return !is_null( $this->getHandler()->read( $resource ) );
    }

}