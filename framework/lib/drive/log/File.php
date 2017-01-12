<?php
namespace framework\lib\drive\log;
/**
* 文件日志驱动类
*/
class File
{
    static $path;

    public function __construct()
    {
        if(empty(self::$path)){
            $log_conf = \framework\lib\Conf::get('log','web');
            $path = $log_conf['path'];
            self::$path = $path;
        }
    }
    /**
     * 纪录日志
     */
    public function log($info, $level)
    {
        $now = @date('Y-m-d H:i:s',time());
        $log_file = self::$path.'/'.date('Ymd',TIMESTAMP).'.log';
        mkFolder(self::$path);
        $url = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
        $url .= " ( act={$_GET['act']}&op={$_GET['op']} ) ";
        $content = "[{$now}] {$url}\r\n{$level}: {$info}\r\n\n";
        file_put_contents($log_file,$content, FILE_APPEND);
    }
}