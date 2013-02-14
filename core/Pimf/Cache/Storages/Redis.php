<?php
/**
 * Pimf
 *
 * PHP Version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://krsteski.de/new-bsd-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to gjero@krsteski.de so we can send you a copy immediately.
 *
 * @copyright Copyright (c) 2010-2011 Gjero Krsteski (http://krsteski.de)
 * @license http://krsteski.de/new-bsd-license New BSD License
 */

/**
 * @package Pimf_Cache_Storages
 * @author Gjero Krsteski <gjero@krsteski.de>
 */
class Pimf_Cache_Storages_Redis extends Pimf_Cache_Storages_Storage
{
  /**
   * The Redis database instance.
   * @var Pimf_Redis
   */
  protected $redis;

  /**
   * @param Pimf_Redis $redis
   */
  public function __construct(Pimf_Redis $redis)
  {
    $this->redis = $redis;
  }

  /**
   * Determine if an item exists in the cache.
   * @param string $key
   * @return bool
   */
  public function has($key)
  {
    return ($this->redis->get($key) !== null);
  }

  /**
   * Retrieve an item from the cache storage.
   * @param string $key
   * @return mixed
   */
  protected function retrieve($key)
  {
    if (!is_null($cache = $this->redis->get($key))) {
      return unserialize($cache);
    }
  }

  /**
   * Write an item to the cache for a given number of minutes.
   *
   * <code>
   *    // Put an item in the cache for 15 minutes
   *    Pimf_Cache::put('name', 'Robin', 15);
   * </code>
   *
   * @param string $key
   * @param mixed $value
   * @param int $minutes
   */
  public function put($key, $value, $minutes)
  {
    $this->forever($key, $value);
    $this->redis->expire($key, $minutes * 60);
  }

  /**
   * Write an item to the cache that lasts forever.
   * @param $key
   * @param $value
   */
  public function forever($key, $value)
  {
    $this->redis->set($key, serialize($value));
  }

  /**
   * Delete an item from the cache.
   * @param string $key
   */
  public function forget($key)
  {
    $this->redis->del($key);
  }
}