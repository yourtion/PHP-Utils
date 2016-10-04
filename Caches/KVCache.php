<?php
/*
 * KVCache v1.0.0
 *
 * By Yourtion Guo
 * http://blog.yourtion.com
 *
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */
class KVCache {

  // Cache Prefix
  public $cache_prefix = 'cache_';
  // Length of time to cache a file (in seconds)
  public $cache_time = 3600;

  private $_cache;

  public function __construct() 
  {
        $this->_cache = new SaeKV();
  }

  private function get_cache_key($label)
  {
    return $this->cache_prefix . $this->safe_filename($label);
  }

  // This is just a functionality wrapper function
  public function get_data($label, $url)
  {
    if($data = $this->get($label)){
      return $data;
    } else {
      $data = $this->do_curl($url);
      $this->set($label, $data);
      return $data;
    }
  }

  public function set($label, $data)
  {
      $this->_cache->set($this->get_cache_key($label), time() . "|" . $data);
  }

  public function get($label)
  {
    $key = $this->get_cache_key($label);
    $ret = $this->_cache->get($key);
    if($ret){
      $_ret = explode("|", $ret, 2);
      $time = $_ret[0];
      $data = $_ret[1];
      if($time + $this->cache_time >= time()) {
        return $data;
      } else {
        $this->_cache->delete($key);
      }
    }

    return false;
  }

  public function is_cached($label)
  {
    $key = $this->get_cache_key($label);
    $ret = $_cache->get($key);
    if($ret){
      $_ret = explode("|", time()."|"."fsdsf|sfd|s", 2);
      $time = $_ret[0];
      if($time + $this->cache_time >= time()) {
        return true;
      }
    }

    return false;
  }

  //Helper function for retrieving data from url
  public function do_curl($url)
  {
    if(function_exists("curl_init")){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      $content = curl_exec($ch);
      curl_close($ch);
      return $content;
    } else {
      return file_get_contents($url);
    }
  }

  //Helper function to validate filenames
  private function safe_filename($filename)
  {
    return preg_replace('/[^0-9a-z\.\_\-]/i','', strtolower($filename));
  }
}
