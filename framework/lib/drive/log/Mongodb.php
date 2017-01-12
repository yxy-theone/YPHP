<?php
namespace framework\lib\drive\log;
/**
* 使用mongodb记录日志驱动类
*/
class Mongodb
{
    /**
     * 纪录日志
     */
    public function log($info, $level)
    {
        throw new \Exception("Mongodb 驱动开发中");
        phpinfo();
        exit();
    }
}