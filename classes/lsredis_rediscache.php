<?
require 'predis-0.8.3/autoload.php';
Predis\Autoloader::register();

class LsRedis_RedisCache extends Core_CacheBase
{

  protected $ttl = 0;
  protected $online_servers = 0;
  protected $redis = null;
  
  /**
   * Creates the caching class instance.
   * @param mixed $Params Specifies the class configuration options
   */
  public function __construct($params = array())
  {
    if (!class_exists('Predis\Client'))
      throw new Phpr_SystemException('Predis is missing.');
    
    $this->ttl = isset($params['TTL']) ? $params['TTL'] : 0;
    
    $servers = isset($params['SERVERS']) ? $params['SERVERS'] : array();

    $config = array();

    foreach ($servers as $server)
    {
      $pos = strpos($server, ':');
      if ($pos === false)
        throw new Phpr_SystemException('Invalid redis server specifier. Please use the following format: 192.168.0.1:11211');
        
      $ip = substr($server, 0, $pos);
      $port = substr($server, $pos+1);

      $server_conf = array(
        'host' => $ip,
        'port' => $port
        );

      $config []= $server_conf;
    }

    $this->redis = new Predis\Client( $config );
  }
  
  /**
   * Adds or updates value to the cache
   * @param string $key The key that will be associated with the item.
   * @param mixed $value The variable to store.
   * @param int $ttl Time To Live; store var in the cache for ttl seconds. After the ttl has passed,
   * the stored variable will be expunged from the cache (on the next request). If no ttl is supplied,
   * the value specified in the TTL parameter of the cache configuration will be used. If there is no
   * TTL value in the cache configuration, the 0 value will will used.
   * @return bool Returns TRUE on success or FALSE on failure.
   */
  protected function set_value($key, $value, $ttl = null)
  {
    if ($ttl === null)
      $ttl = $this->ttl;

    $key = $this->fix_key( $key );

    $value = serialize( $value );

    try
    {
      $result = $this->redis->set( $key, $value );

      if ( $ttl )
        $this->redis->expire( $key, intval( $ttl ) );
      
      return $result == 1;
    } catch (exception $e) 
    {
      return false;
    }
  }

  /**
   * Returns value from the cache
   * @param mixed $key The key or array of keys to fetch.
   */
  protected function get_value($key)
  {
    $result = array();

    try
    {
      if (!is_array($key))
        return $this->get_cache_value($key);

      foreach ($key as $key_value) {
        $result[ $key_value ] = $this->get_cache_value( $key_value );
      }
    } catch (exception $e)
    {
      return false;
    }
    
    return $result;
  }

  protected function get_cache_value($key)
  {
    $key = $this->fix_key( $key );

    $contents = $this->redis->get( $key );
    return @unserialize( $contents );
  }

  /**
  * Prevents @key from colliding with other Redis values
  * @param string $key The key
  */
  protected function fix_key( $key )
  {
    return Phpr::$request->getRootUrl().'|'.$key;
  }

}

?>
