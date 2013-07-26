<?php
/**
 * HTTP Caching for the Slim Framework
 *
 * Use this class if you use Slim caching on resources which are being changed dynamically.
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
namespace Slim\Http\Caching;

/**
 * Class Resource
 * @package Slim\Http\Caching
 */
class Resource implements IResource {

    /**
     * URI of the current resource
     *
     * @access protected
     * @var String
     */
    protected $_resource = null;

    /**
     * ETag for the current resource
     *
     * @access protected
     * @var String
     */
    protected $_eTag = null;

    /**
     * Time when the resource has changed for the last time
     *
     * @var String
     */
    protected $_lastModified = null;

    /**
     * Lifetime in hours for the current resource
     *
     * @access protected
     * @var int
     */
    protected $_lifeTime = null;

    /**
     * @var String
     */
    protected $_expiryDate = null;

    /**
     * @param null $eTag
     */
    public function setETag( $eTag = null ) {
        $this->_eTag = $eTag;
    }

    /**
     * @return null
     */
    public function getETag() {
        return $this->_eTag;
    }

    /**
     * @param null $lifeTime
     */
    public function setLifeTime( $lifeTime = 0 ) {
        $this->_lifeTime = $lifeTime;
    }

    /**
     * @return null
     */
    public function getLifeTime() {
        return $this->_lifeTime;
    }

    /**
     * @param null $resource
     */
    public function setResource( $resource = null ) {
        $this->_resource = $resource;
    }

    /**
     * @return null
     */
    public function getResource() {
        return $this->_resource;
    }

    /**
     * @param String $expiryDate
     */
    public function setExpiryDate( $expiryDate = null ) {
        $this->_expiryDate = $expiryDate;
    }

    /**
     * @return String
     */
    public function getExpiryDate() {
        return $this->_expiryDate;
    }

    /**
     * @param String $lastModified
     */
    public function setLastModified( $lastModified = null ) {
        if( !is_null( $lastModified ) && !strtotime( $lastModified ) )
            throw new \Exception( 'invalid date given' );
        $this->_lastModified = $lastModified;
    }

    /**
     * @return String
     */
    public function getLastModified() {
        return strtotime( $this->_lastModified );
    }

}