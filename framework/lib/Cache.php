<?php
namespace framework\lib;

/**
 * 缓存类 file,redis,memcache
 */
class Cache
{
    /**
     * 1.确定缓存方式
     * 2.操作
     */
    private static $cache_obj;

    /**
     * 确定缓存存储方式
     * @return 缓存驱动类实例
     */
    private static function init($drive){
        if ($drive == null) {
            if(!is_object(self::$cache_obj)){
                $cache_conf = Conf::get('log','web');            
                $drive = $cache_conf['drive'];
                $class = '\framework\lib\drive\cache\\'.ucfirst($drive).'Cache';
                self::$cache_obj = new $class;
            }
            return self::$cache_obj;
        }
        $class = '\framework\lib\drive\cache\\'.ucfirst($drive).'Cache';
        $cache_drive = new $class;
        return $cache_drive;
    }

    /**
     * 读缓存
     * @param  string $key 缓存key
     * @return 缓存value
     */
    public static function get($key, $drive=null){
        return self::init($drive)->get($key);
    }

    /**
     * 写缓存
     * @param string $key 缓存key
     * @param bool 返回true或者false 设置成功或失败
     */
    public static function set($key,$data, $drive=null){
        return self::init($drive)->set($key,$data);
    }

    /**
     * 删缓存
     * @param  string $key 缓存key
     * @return bool 返回true或者false 删除成功或失败
     */
    public static function del($key, $drive=null){
        return self::init($drive)->del($key);
    }
}