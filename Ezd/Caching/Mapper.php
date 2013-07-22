<?php
/**
 * Class handles caching of the resources
 *
 * @author Magnus Buk <Magnus.Buk@1-2-3.tv>
 */
namespace Ezd\Caching;
use Slim\Slim;

class Mapper {

    /**
     * Array in which the dynamic resources are stored which should be cached
     * (Key = Resource; Value = Lifetime in hours)
     *
     * @access protected
     * @var array
     */
    protected $_resourceWildcards = Array(

        // Moderator data and list
        '/fetch/moderator/data/' => 24,
        '/fetch/moderator/list/' => 24,

        // Statics (e.g. tac, imprint etc.)
        '/fetch/statics/' => 168,

        // City and Street names
        '/fetch/city/' => 168,
        '/fetch/street/' => 168,

        // Brands and other stuff
        '/fetch/brands/' => 48

    );

    /**
     * @var Slim
     */
    protected $_app;

    /**
     * The Array where the cached resources are stored
     *
     * @var array
     */
    protected $_data = Array();

    /**
     * DB connection
     *
     * @var null
     */
    protected $_db = null;

    /**
     * the requested resource as string
     *
     * @var String
     */
    protected $_resource = null;

    /**
     * Constructor
     *
     * @param $db
     */
    public function __construct( $db ) {
        $this->_db = $db;
        $this->_getData();
    }

    /**
     * Sets the Slim instance
     *
     * @access public
     * @param Slim $app
     * @return void
     */
    public function setApplication( Slim $app ) {
        $this->_app = $app;
    }

    /**
     * Get the Slim instance
     *
     * @access public
     * @return Slim
     * @return Slim
     */
    public function getApplication() {
        return $this->_app;
    }

    /**
     * Checks if the resource is cached. if yes set the etag
     *
     * @access public
     * @return bool
     */
    public function setHeaders() {

        $this->_prepareResource();

        if( array_key_exists( $this->getResource(), $this->_data ) ) {

            $lifetime = $this->_data[$this->getResource()]['lifetime'];
            $eTag = $this->_data[$this->getResource()]['etag'];

            // Set ETag
            $this->getApplication()->etag( $eTag );

            // Also set the "expires"-Header
            $this->getApplication()->expires( '+' . $lifetime. ' hours' );
        }

        return true;

    }

    /**
     * Get the requested resource as a string
     *
     * @access public
     * @return string
     */
    public function getResource() {
        return $this->_resource;
    }

    /**
     * Fetches the caching data from the database
     *
     * @access private
     * @return void
     */
    private function _getData() {
        $query = $this->_db->getDb()->execute( "SELECT * FROM api_cache_lifetime" );
        while( $res = $query->fetchRow() ) {
            $this->_data[$res['resource']] = $res;
        }
    }

    /**
     * Prepares the resource of the current request
     *
     * @access private
     * @return void
     */
    private function _prepareResource() {

        $resource = $this->getApplication()->environment()->offsetGet( 'PATH_INFO' );

        if( substr( $resource, -1 ) == '/' )
            $this->_resource = $resource;
        else
            $this->_resource = $resource . '/';

        // Check if the resource can be matched with a wildcard resource
        foreach( $this->_resourceWildcards as $wildcard => $lifetime ) {
            if( $wildcard == substr( $this->_resource, 0, strlen( $wildcard ) ) ) {
                if( !array_key_exists( $this->_resource, $this->_data ) ) {
                    $this->_db->getDb()->execute(
                        "EXEC usp_app_generate_cache_resource '{$this->getResource()}', {$lifetime}"
                    );
                    // Update data
                    $this->_getData();
                }
            }
        }
    }
}