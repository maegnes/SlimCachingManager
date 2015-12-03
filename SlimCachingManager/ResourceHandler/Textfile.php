<?php
/**
 * Textfile Resource Handler stores cached resource data into a textfile
 *
 * Write and read cached resources to textfile
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
namespace SlimCachingManager\ResourceHandler;

use SlimCachingManager\Resource;

class Textfile implements IResourceHandler
{

    /**
     * Holds the cached resources
     *
     * @access public
     * @var array
     */
    protected $_data = Array();

    /**
     * @var string
     */
    private $_file = 'cache.txt';

    /**
     * @access public
     */
    public function __construct()
    {
        $this->_readData();
    }

    /**
     * Call gc() on destruct
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        $this->gc();
    }

    /**
     * Read data from database
     *
     * @access private
     * @return void
     */
    public function _readData()
    {

        if (!file_exists($this->_file)) {
            file_put_contents($this->_file, 'a:0:{}');
        }
        $this->_data = unserialize(file_get_contents($this->_file));

    }

    /**
     * Read data for given resource
     *
     * @access public
     *
     * @param String $resource
     *
     * @return \SlimCachingManager\IResource
     */
    public function read($resource = null)
    {
        return (is_array($this->_data[$resource])) ? $this->getObject($this->_data[$resource]) : null;
    }

    /**
     * Write resource to caching data
     *
     * @param $resource
     * @param $lifetime
     *
     * @return bool
     */
    public function write($resource, $lifetime)
    {

        if (!array_key_exists($resource, $this->_data)) {

            $this->_data[$resource] = Array(
                'resource'      => $resource,
                'etag'          => uniqid('ET'),
                'lifetime'      => $lifetime,
                'expiry_date'   => date('Y-m-d H:i:s', strtotime('+' . $lifetime . ' hours')),
                'last_modified' => date('Y-m-d H:i:s', time())
            );

            file_put_contents($this->_file, serialize($this->_data));

            $this->_readData();

        }

        return true;

    }

    /**
     * Garbage collector - delete expired caching data
     *
     * @access public
     * @return bool
     */
    public function gc()
    {

        foreach ($this->_data as $resource => $data) {
            if (strtotime($data['expiry_date']) < time()) {
                unset($this->_data[$resource]);
            }
        }

        file_put_contents($this->_file, serialize($this->_data));

    }

    /**
     * Returns the cached resource as a object
     *
     * @access public
     *
     * @param array $res
     *
     * @return Resource
     */
    public function getObject($res = Array())
    {
        $obj = new Resource();
        $obj->setETag($res['etag']);
        $obj->setResource($res['resource']);
        $obj->setLifeTime($res['lifetime']);
        $obj->setExpiryDate($res['expiry_date']);
        $obj->setLastModified($res['last_modified']);
        return $obj;
    }
}
