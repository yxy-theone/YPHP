<?php 
namespace framework\lib;
/**
* 文件上传类,单文件上传,多文件上传
* 默认上传图片
*/
class Upload{
    private static $fileName;
    private static $maxSize;
    private static $allowMime;
    private static $allowExt;
    private static $uploadPath;
    private static $imgFlag;
    private static $fileInfo;
    private static $error;
    private static $ext;
    private static $uniName;
    private static $destination;

    /**
     * 检测上传文件是否出错
     * @return boolean
     */
    private static function checkError(){
        if(!is_null(self::$fileInfo)){
            if(self::$fileInfo['error']>0){
                switch(self::$fileInfo['error']){
                    case 1:
                        self::$error='超过了PHP配置文件中upload_max_filesize选项的值';
                        break;
                    case 2:
                        self::$error='超过了表单中MAX_FILE_SIZE设置的值';
                        break;
                    case 3:
                        self::$error='文件部分被上传';
                        break;
                    case 4:
                        self::$error='没有选择上传文件';
                        break;
                    case 6:
                        self::$error='没有找到临时目录';
                        break;
                    case 7:
                        self::$error='文件不可写';
                        break;
                    case 8:
                        self::$error='由于PHP的扩展程序中断文件上传';
                        break;
                        
                }
                return false;
            }else{
                return true;
            }
        }else{
            self::$error='文件上传出错';
            return false;
        }
    }
    /**
     * 检测上传文件的大小
     * @return boolean
     */
    private static function checkSize(){
        if(self::$fileInfo['size']>self::$maxSize){
            self::$error='上传文件过大';
            return false;
        }
        return true;
    }
    /**
     * 检测扩展名
     * @return boolean
     */
    private static function checkExt(){
        self::$ext=strtolower(pathinfo(self::$fileInfo['name'],PATHINFO_EXTENSION));
        if(!in_array(self::$ext,self::$allowExt)){
            self::$error='不允许的扩展名';
            return false;
        }
        return true;
    }
    /**
     * 检测文件的类型
     * @return boolean
     */
    private static function checkMime(){
        if(!in_array(self::$fileInfo['type'],self::$allowMime)){
            self::$error='不允许的文件类型';
            return false;
        }
        return true;
    }
    /**
     * 检测是否是真实图片
     * @return boolean
     */
    private static function checkTrueImg(){
        if(self::$imgFlag){
            if(!@getimagesize(self::$fileInfo['tmp_name'])){
                self::$error='不是真实图片';
                return false;
            }
            return true;
        }
    }
    /**
     * 检测是否通过HTTP POST方式上传上来的
     * @return boolean
     */
    private static function checkHTTPPost(){
        if(!is_uploaded_file(self::$fileInfo['tmp_name'])){
            self::$error='文件不是通过HTTP POST方式上传上来的';
            return false;
        }
        return true;
    }
    /**
     * 检测目录不存在则创建
     */
    private static function checkUploadPath(){
        if(!file_exists(self::$uploadPath)){
            mkdir(self::$uploadPath,0777,true);
        }
    }
    /**
     * 产生唯一字符串
     * @return string
     */
    private static function getUniName(){
        return md5(uniqid(microtime(true),true));
    }
    /**
     * 上传文件
     * @return string
     */
    public static function uploadFile($fileName,$uploadPath='./uploads',$imgFlag=true,$maxSize=5242880,$allowExt=array('jpeg','jpg','png','gif'),$allowMime=array('image/jpeg','image/png','image/gif')){
        self::$fileName=$fileName;
        self::$maxSize=$maxSize;
        self::$allowMime=$allowMime;
        self::$allowExt=$allowExt;
        self::$uploadPath=$uploadPath;
        self::$imgFlag=$imgFlag;
        self::$fileInfo=self::$fileName;
        $success = false;
        if(self::checkError()&&self::checkSize()&&self::checkExt()&&self::checkMime()&&self::checkTrueImg()&&self::checkHTTPPost()){
            self::checkUploadPath();
            self::$uniName=self::getUniName();
            self::$destination=self::$uploadPath.'/'.self::$uniName.'.'.self::$ext;
            if(@move_uploaded_file(self::$fileInfo['tmp_name'], self::$destination)){
                $success = ture;
                $message = self::$destination;
            }else{
                self::$error='文件移动失败';
                $message = self::$error;
            }
        }else{
            $message = self::$error;
        }
        return [$success,$message];
    }
}