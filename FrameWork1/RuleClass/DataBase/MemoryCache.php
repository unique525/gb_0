<?php
/**
 * Created by PhpStorm.
 * User: zhangchi
 * Date: 15-8-13
 * Time: 下午12:20
 */

class MemoryCache {


    /**
     * 单例实例
     * @var object
     */
    private static $instance = null;


    /**
     * 单例模式构建操作类
     * @return object
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Memcached();
            self::$instance->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);

            global $incMemcachedServers;
            self::$instance->addServers($incMemcachedServers);
        }
        return self::$instance;
    }
} 