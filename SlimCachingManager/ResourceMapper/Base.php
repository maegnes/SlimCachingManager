<?php
/**
 * Slim Caching Manager for the Slim Framework
 *
 * Use this class if you use Slim caching on resources which are being changed dynamically (e.g. background tasks)
 * You can use ResourceHandler to store the cached data (Resource, Lifetime) wherever you want.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 * @version 1.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace SlimCachingManager\ResourceMapper;

use Slim\Slim;
use Exception;
use SlimCachingManager\IResource;
use SlimCachingManager\IResourceHandler;

/**
 * Slim ResourceMapper
 *
 * ResourceMapper checks if current resource has caching entries and/or adds them to selected data adapter (via IResourceHandler)
 *
 * @package Slim\Http\Caching
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
abstract class Base {

    /**
     * Holds the resources which should be cached
     *
     * @var array
     */
    public static $_resourceWildcards = Array();

    /**
     * Slim application instance
     *
     * @access protected
     * @var Slim
     */
    protected $_app = null;

    /**
     * ResourceHandler instance
     *
     * @access protected
     * @var IResourceHandler
     */
    protected $_handler = null;

    /**
     * URI of the current resource
     *
     * @access protected
     * @var String
     */
    protected $_resource = null;

    /**
     * Constructor
     *
     * @access public
     * @return self
     */
    public function __construct() {
        return $this;
    }

    /**
     * Injects the current Slim application instance
     *
     * @param Slim $app
     * @return self
     */
    public function setApplication( Slim $app ) {
        $this->_app = $app;
        return $this;
    }

    /**
     * Returns the current Slim application instance
     *
     * @access public
     * @return Slim
     */
    public function getApplication() {
        if( !$this->_app instanceof Slim )
            throw new Exception( 'No valid Slim instance was set!' );
        return $this->_app;
    }

    /**
     * Set the resource handler
     *
     * @access public
     * @param IResourceHandler $handler
     * @return self
     */
    public function setHandler( IResourceHandler $handler ) {
        $this->_handler = $handler;
        return $this;
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
     * (1) Prepares the resource uri and formats it to correct syntax.
     * (2) Also checks if the current resource matches to any resources in self::$_resourceWildcards
     * (3) If yes, the resources will be added to our ResourceHandler
     *
     * @access protected
     * @return void
     */
    protected function _prepareResource() {

        if( substr( $this->getApplication()->request()->getResourceUri(), -1 ) == '/' )
            $this->_resource = $this->getApplication()->request()->getResourceUri();
        else
            $this->_resource = $this->getApplication()->request()->getResourceUri() . '/';

        // Check if the resource can be matched with a wildcard resource
        foreach( self::$_resourceWildcards as $wildcard => $lifetime ) {
            if( $wildcard == substr( $this->_resource, 0, strlen( $wildcard ) ) )
                    $this->getHandler()->write( $this->_resource, $lifetime );
        }
    }

    /**
     * Adds the given resources to the resources which should be cached (wildcard possible)
     *
     * Array( 'resource' => lifetime );
     * e.g. Array( '/fetch/test/', 24 );
     *
     * @param array $data
     * @return void
     */
    public static function addResourceWildcard( $data = null, $lifetime = 0 ) {
        if( is_array( $data ) )
            self::$_resourceWildcards = array_merge( self::$_resourceWildcards, $data );
        else
            self::$_resourceWildcards[$data] = $lifetime;
    }

}