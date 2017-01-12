<?php
namespace framework\lib;
use mysqli;
/**
 * mysqli驱动
 */
class Db{

	const  db_default_index=0;

	private static $link = array();

	private static $iftransacte = true;

	private static function connect($host=self::db_default_index){
		if (is_object(self::$link[$host])) return;
		$conf = Conf::get($host,'db');
		self::$link[$host] = @new mysqli($conf['dbhost'], $conf['dbuser'], $conf['dbpwd'], $conf['dbname'], $conf['dbport']);
		if (mysqli_connect_errno()) exit("Db Error: database connect failed");
		$query_string = "SET CHARACTER_SET_CLIENT = utf8,
		                 CHARACTER_SET_CONNECTION = utf8,
		                 CHARACTER_SET_DATABASE = utf8,
		                 CHARACTER_SET_RESULTS = utf8,
		                 CHARACTER_SET_SERVER = utf8,
		                 COLLATION_CONNECTION = utf8_general_ci,
		                 COLLATION_DATABASE = utf8_general_ci,
		                 COLLATION_SERVER = utf8_general_ci,
		                 sql_mode=''";
		//进行编码声明
		if (!self::$link[$host]->query($query_string)){
			exit("Db Error: ".mysqli_error(self::$link[$host]));
		}
	}

    public static function ping($host =self::db_default_index) {
        if (is_object(self::$link[$host])) {
            self::$link[$host]->close();
            self::$link[$host] = null;
        }
    }

	/**
	 * 执行查询
	 *
	 * @param string $sql
	 * @return mixed
	 */
	public static function query($sql, $host =self::db_default_index){
		self::connect($host);
		$query = self::$link[$host]->query($sql);
		if ($query === false){
		    $error = 'Db Error: '.mysqli_error(self::$link[$host]);
			Log::record($error."\r\n".$sql);
			return false;
		}else {
			return $query;
		}
	}

	/*
	 * 多个查询
	 */
	public static function multi_query2data($sql, $host =self::db_default_index){
		self::connect($host);
		if (self::$link[$host]->multi_query($sql)) {
			$array = array();
			while (true) {
				if ($result=self::$link[$host]->store_result()) {
					while ($tmp=mysqli_fetch_array($result,MYSQLI_ASSOC)){
						$array[] = $tmp;
					}
					$result->free();
				}
				if (self::$link[$host]->more_results()) {
					self::$link[$host]->next_result();
				} else {
					break;
				}
			}
			return $array;
		}else{
			$error = 'Db Error: '.mysqli_error(self::$link[$host]);
			Log::record($error."\r\n".$sql);
			return array();
		}
	}

	/**
	 * 取得数组
	 *
	 * @param string $sql
	 * @return bool/null/array
	 */
	public static function query2data($sql, $host =self::db_default_index){
		self::connect($host);
		$result = self::query($sql, $host);
		if ($result === false) return array();
		$array = array();
		while ($tmp=mysqli_fetch_array($result,MYSQLI_ASSOC)){
			$array[] = $tmp;
		}
		return $array;
	}

	/**
	 * 取得上一步插入产生的ID
	 * @return int
	 */
	public static function getLastId($host =self::db_default_index){
		self::connect($host);
		$id = mysqli_insert_id(self::$link[$host]);
		if (!$id){
		    $result = self::query('SELECT last_insert_id() as id',$host);
		    if ($result === false) return false;
			$id = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$id = $id['id'];
		}
		return $id;
	}

	/**
	 * 执行SQL语句
	 *
	 * @param string $sql 待执行的SQL
	 * @return
	 */
	public static function execute($sql, $host =self::db_default_index){
		self::connect($host);
		$result = self::query($sql,$host);
		return $result;
	}
	
	/**
	 * 取得服务器信息
	 *
	 * @return string
	 */
	public static function getServerInfo($host =self::db_default_index){
		self::connect($host);
		$result = mysqli_get_server_info(self::$link[$host]);
		return $result;
	}

    public static function beginTrans($host =self::db_default_index){
    	self::connect($host);
    	if (self::$iftransacte){
    		self::$link[$host]->autocommit(false);//关闭自动提交
    	}
    	self::$iftransacte = false;
    }

    public static function commit($host =self::db_default_index){
    	if (!self::$iftransacte){
    		$result = self::$link[$host]->commit();
    		self::$link[$host]->autocommit(true);//开启自动提交
    		self::$iftransacte = true;
    		if (!$result) exit("Db Error: ".mysqli_error(self::$link[$host]));
    	}
    }

    public static function rollback($host =self::db_default_index){
    	if (!self::$iftransacte){
    		$result = self::$link[$host]->rollback();
    		self::$link[$host]->autocommit(true);
    		self::$iftransacte = true;
    		if (!$result) exit("Db Error: ".mysqli_error(self::$link[$host]));
    	}
    }
}
