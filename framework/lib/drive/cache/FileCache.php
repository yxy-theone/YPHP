<?php
namespace framework\lib\drive\cache;
/**
* 文件缓存驱动类
*/
class FileCache
{
    static $path;

    public function __construct()
    {
        if (empty(self::$path)) {
            $cache_conf = \framework\lib\Conf::get('cache','web');
            $path = $cache_conf['path'];
            self::$path = $path;
        }
    }

    private function _cachefile($key){
        return self::$path."/".$key.".php";
    }

    /**
     * 内容写入文件
     *
     * @param string $filepath 待写入内容的文件路径
     * @param string/array $data 待写入的内容
     * @param  string $mode 写入模式，如果是追加，可传入“append”
     * @return bool
     */
    private function write_file($filepath, $data, $mode = null)
    {
        if (!is_array($data) && !is_scalar($data)) {
            return false;
        }
        $data = var_export($data, true);

        $data = "<?php  return ".$data.";";
        $mode = $mode == 'append' ? FILE_APPEND : null;
        if (false === file_put_contents($filepath,($data),$mode)){
            return false;
        }else{
            return true;
        }
    }

    public function get($key){
        $filename = realpath($this->_cachefile($key));
        if (is_file($filename)){
            return require($filename);
        }else{
            return false;
        }
    }

    public function set($key, $value){
        if (false == $this->write_file($this->_cachefile($key),$value)){
            return false;
        }else{
            return true;
        }
    }

    public function del($key){
        $filename = realpath($this->_cachefile($key));
        if (is_file($filename)) {
            @unlink($filename);
        }else{
            return false;
        }
        return true;
    }
}