<?php
namespace framework\lib\drive\log;
/**
* 使用mysql记录日志驱动类
*/
class Mysql
{
    static $model;

    public function __construct()
    {
        if (empty(self::$model)) {
            $log_conf = \framework\lib\Conf::get('log','web');
            self::$model = $log_conf['mysql'];
        }
    }
    /**
     * 纪录日志
     */
    public function log($info, $level)
    {
        $data = [];
        $data['app'] = APP;
        $data['route'] = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
        $data['message'] = $info;
        $data['level'] = $level;
        $data['ip'] = getIp();
        $data['createtime'] = TIMESTAMP;
        M(self::$model)->addLog($data);//入库
    }
}