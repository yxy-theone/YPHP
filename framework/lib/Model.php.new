<?php
namespace framework\lib;
/**
 * 核心文件
 * 模型类
*/
class Model
{
    private $options=array();//拼接sql的所有参数
    private $db=null;//存储ModelDb对象

    /**
     * 构造方法 实例化ModelDb并缓存到赋值$db属性
     * @param string $db_index 数据库索引
     */
    public function __construct($db_index=0){
        if (!is_object($this->db)){
			$this->db = new ModelDb($db_index);
		}
    }

    /**
     * 魔术方法 __call 当调用不存在的方法时被调用 填充$option元素以拼接sql
     * @param  string $method 方法名
     * @param  string $args   参数
     * @return $this 自身对象
     */
    public function __call($method,$args) {
        if(in_array(strtolower($method),array('field','table','order','where','on','join','limit','having','group','lock','master','distinct','index','attr'),true)) {
            if(empty($args[0])){
                return $this;
            }
            $this->options[strtolower($method)] =$args[0];
            return $this;
        }else{
            $error = 'Model Error:  Function '.$method.' is not exists!';
            exit($error);
        }
    }

    /**
     * 分页方法
     * @param  int $num 每页的数量
     * @return $this 自身对象
     */
    public function page($num){
        if(empty(intval($num))){
            return $this;
        }
        $this->options['page'] =$num;
        Paging::setEachNum($num);//设置每页显示数量
        Paging::setTotalNum($this->_getval('COUNT(*) AS _count_'));//设置总数量
        return $this;
    }

    /**
     * 取得上一步插入产生的ID
     * @return int
     */
    public function getLastId() {
        return $this->db->getLastId();
    }

    /**
     * 执行查询
     * @return array  查询结果(二维数组)
     */
    public function select(){
        $resultSet = $this->db->select($this->options);
        $this->options=array();//清空
        return $resultSet;
    }

    /**
     * 执行查询 获取一条记录
     * @return array  查询结果(一维数组)
     */
    public function get(){
        $this->options['limit'] = 1;
        $result = $this->db->select($this->options);
        $this->options=array();//清空
        if(empty($result)) {
            return array();
        }
        return $result[0];
    }

    /**
     * _getval 获取单一值
     * @param  string  $field           字段
     * @param  boolean $isclear_options 是否清空$options
     * @return 单一值或null
     */
    private function _getval($field,$isclear_options=false){
        if(empty($field)){
            return null;
        }
        //获取$option，并更新field元素值，取1条并直接执行sql
        $options=$this->options;
        $options['field'] = $field;
        $options['limit'] = 1;
        $result = $this->db->select($options);
        if($isclear_options){
            $this->options=array();
        }
        if(!empty($result)) {
            return reset($result[0]);
        }
        return null;
    }

    /**
     * getval 获取单一值 并清空$options参数
     * @param  string  $field  字段
     * @return 单一值或null
     */
    public function getval($field) {
        return $this->_getval($field,true);
    }

    /**
     * 获取总数
     * @return int 总数
     */
    public function count(){
        return $this->getval('COUNT(*) AS _count_');
    }

    /**
     * insert 插入
     * @param  array   $data  插入数组
     * @param  boolean $is_getLastId 是否返回插入id
     * @return boolean/int
     */
    public function insert($data,$is_getLastId=false){
        if(empty($data)) return false;
        $result = $this->db->insert($data,$this->options,false);
        $this->options=array();
        if($is_getLastId===true && false !== $result) {
            $insertId  =  $this->getLastId();
            if($insertId) {
                return $insertId;
            }
        }
        return $result;
    }

    /**
     * replace 插入 如果发现表中已经有此行数据（根据主键或者唯一索引判断）则先删除此行数据，然后插入新的数据。否则，直接插入新数据。
     * @param  array   $data  插入数组
     * @return boolean 插入成功或失败
     */
    public function replace($data){
        if(empty($data)) return false;
        $result = $this->db->insert($data,$this->options,true);
        $this->options=array();
        return $result;
    }

    /**
     * update 修改
     * @param  array  $data  修改数组
     * @return boolean
     */
    public  function  update($data){
        $result = $this->db->update($data,$this->options);
        $this->options=array();
        return $result;
    }

    /**
     * delete 删除
     * @return boolean
     */
    public function delete(){
        $result=$this->db->delete($this->options);
        $this->options=array();
        return $result;
    }

    /**
     * insertAll 批量插入
     * @param  array  $dataList  待批量插入的数据(二维数组)
     * @param  boolean $replace  是否使用REPLACE替换INSERT，避免主键冲突
     * @return boolean
     */
    public function insertAll($dataList,$replace=false){
        if(empty($dataList)) return false;
        // 写入数据到数据库
        $result = $this->db->insertAll($dataList,$this->options,$replace);
        $this->options=array();
        if(false !== $result ) return true;
        return $result;
    }

    /**
     * insert_duplicate_updateAll  插入重复时更新
     */
    public function insert_duplicate_updateAll($dataList,$update_data){
        if(empty($dataList)) return false;
        $result = $this->db->insert_duplicate_updateAll($dataList,$this->options,$update_data);
        $this->options=array();
        if(false !== $result ) return true;
        return false;
    }

    /**
     * 直接执行sql语句
     * @param  string $sql sql语句
     * @return 执行结果
     */
	public function query($sql){
        return $this->db->query($sql);
	}
}


/**
 * 完成模型SQL组装
 */
class ModelDb
{
    private $db_index;
    private $str_replace = false;//是否在escapeString函数中之下str替换 在增改操作下执行替换

    public function __construct($host=0){
        $this->db_index=$host;
    }

    protected $comparison      = array('eq'=>'=','neq'=>'<>','gt'=>'>','egt'=>'>=','lt'=>'<','elt'=>'<=','notlike'=>'NOT LIKE','like'=>'LIKE','in'=>'IN','not in'=>'NOT IN');
    // 查询表达式
    protected $selectSql  =  'SELECT%DISTINCT% %FIELD% FROM %TABLE%%INDEX%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT% %UNION%';

    /**
     * 执行查询
     * @param  array  $options 拼接sql的参数
     * @return array  查询结果
     */
    public function select($options=array()) {
		$sql = $this->buildSelectSql($options);
        return Db::query2data($sql,$this->db_index);
    }

    public function buildSelectSql($options=array()) {
        if (is_numeric($options['page'])){
            if ($options['limit'] !== 1){
                $options['limit'] = Paging::getLimitStart().",".Paging::getEachNum();
            }
        }
        $sql  = $this->parseSql($this->selectSql,$options);
        $sql .= $this->parseLock(isset($options['lock'])?$options['lock']:false);
        return $sql;
    }

    public function parseSql($sql,$options=array()){
        $sql   = str_replace(
            array('%TABLE%','%DISTINCT%','%FIELD%','%JOIN%','%WHERE%','%GROUP%','%HAVING%','%ORDER%','%LIMIT%','%UNION%','%INDEX%'),
            array(
                $this->parseTable($options),
                $this->parseDistinct(isset($options['distinct'])?$options['distinct']:false),
                $this->parseField(isset($options['field'])?$options['field']:'*'),
                $this->parseJoin(isset($options['on'])?$options:array()),
                $this->parseWhere(isset($options['where'])?$options['where']:''),
                $this->parseGroup(isset($options['group'])?$options['group']:''),
                $this->parseHaving(isset($options['having'])?$options['having']:''),
                $this->parseOrder(isset($options['order'])?$options['order']:''),
                $this->parseLimit(isset($options['limit'])?$options['limit']:''),
                $this->parseUnion(isset($options['union'])?$options['union']:''),
                $this->parseIndex(isset($options['index'])?$options['index']:'')
            ),$sql);
        return $sql;
    }

	protected function parseUnion(){
		return '';
	}

	protected function parseLock($lock=false) {
	    if(!$lock) return '';
	    return ' FOR UPDATE ';
	}

	protected function parseIndex($value){
		return empty($value) ? '':' USE INDEX ('.$value.') ';
	}

    /**
     * 对value过滤
     * @param  mixed $value 过滤前的值
     * @return mixed        过滤后的值
     */
    protected function parseValue($value) {
        if(is_string($value) || is_numeric($value)) {
            $value = '\''.$this->escapeString($value).'\'';
        }elseif(isset($value[0]) && is_string($value[0]) && strtolower($value[0]) == 'exp'){
            $value = $value[1];
        }elseif(is_array($value)) {
            $value = array_map(array($this, 'parseValue'),$value);
        }elseif(is_null($value)){
            $value = 'NULL';
        }
        return $value;
    }

    protected function parseField($fields) {
        if(is_string($fields) && strpos($fields,',')) {
            $fields    = explode(',',$fields);
        }
        if(is_array($fields)) {
            //字段别名定义
            $array   =  array();
            foreach ($fields as $key=>$field){
                if(!is_numeric($key))
                    $array[] =  $key.' AS '.$field;
                else
                    $array[] =  $field;
            }
            $fieldsStr = implode(',', $array);
        }elseif(is_string($fields) && !empty($fields)) {
            $fieldsStr = $fields;
        }else{
            $fieldsStr = '*';
        }
        return $fieldsStr;
    }

    /**
     * 对表名过滤
     * @param  array  $options 拼接sql的参数
     * @return string          表名
     */
    protected function parseTable($options) {
    	if ($options['on']) return null;
    	$tables = $options['table'];
        if(is_array($tables)) {// 别名定义
            $array   =  array();
            foreach ($tables as $table=>$alias){
                if(!is_numeric($table))
                    $array[] =  $table.' '.$alias;
                else
                    $array[] =  $table;
            }
            $tables  =  $array;
        }elseif(is_string($tables)){
            $tables  =  explode(',',$tables);
        }
        return implode(',',$tables);
    }

    protected function parseWhere($where) {
        $whereStr = '';
        if(is_string($where)) {
            $whereStr = $where;
        }elseif(is_array($where)){
            foreach ($where as $key=>$val){
                $whereStrTemp = '';
                // 查询字段的安全过滤
                if(!preg_match('/^[A-Z_\|\&\-\(\).a-z0-9]+$/',trim($key))){
                    exit("不合法查询");
                }
                $key = trim($key);
                // 多条件支持   更高级模式   (a|b)&(c|d)
                if(strpos($key,'|')||strpos($key,'&')&&is_array($val)){
                    $multi_keys=array();
                    preg_match_all('/[^\(^\)^|^&]+/',$key ,$multi_keys);
                    $m_strs=array();
                    $r_keys=array();
                    $r_clones=array();
                    foreach($multi_keys[0] as $m=>$k){
                        $m_strs[]=$this->parseWhereItem($k,$val[$m]);
                        $r_keys[]="/".$k."/";
                        $r_clones[]="{".$m."}";
                    }
                    $whereStrTemp.=str_replace(array("&","|"),array(" AND "," OR "),str_replace($r_clones,$m_strs,preg_replace($r_keys,$r_clones,$key,1)));
                }else{
                    $whereStrTemp   .= $this->parseWhereItem($key,$val);
                }
                if(!empty($whereStrTemp)) {
                    $whereStr .= '( '.$whereStrTemp.' )AND';
                }
            }
            $whereStr = substr($whereStr,0,-3);
        }
        return empty($whereStr)?'':' WHERE '.$whereStr;
    }

    // where子单元分析
    protected function parseWhereItem($key,$val) {
        $whereStr = '';
        if(is_array($val)) {
            if(preg_match('/^(EQ|NEQ|GT|EGT|LT|ELT|NOTLIKE|LIKE)$/i',$val[0])) { // 比较运算
                $whereStr .= $key.' '.$this->comparison[strtolower($val[0])].' '.$this->parseValue($val[1]);
            }elseif('exp'==strtolower($val[0])){ // 使用表达式
                $whereStr .= $val[1];
            }elseif('in'==strtolower($val[0])){ // IN 运算
                if(isset($val[2]) && 'exp'==$val[2]) {
                    $whereStr .= $key.' '.strtoupper($val[0]).' '.$val[1];
                }else{
                    if (empty($val[1])){
                        $whereStr .= $key.' '.strtoupper($val[0]).'(\'\')';
                    }elseif(is_string($val[1]) || is_numeric($val[1])) {
                         $val[1] =  explode(',',$val[1]);
                         $zone   =   implode(',',$this->parseValue($val[1]));
                         $whereStr .= $key.' '.strtoupper($val[0]).' ('.$zone.')';
                    }elseif(is_array($val[1])){
                        $zone   =   implode(',',$this->parseValue($val[1]));
                        $whereStr .= $key.' '.strtoupper($val[0]).' ('.$zone.')';
                    }
                }
            }elseif(preg_match('/BETWEEN/i',$val[0])){
                $data = is_string($val[1])? explode(',',$val[1]):$val[1];
                if($data[0] && $data[1]) {
                    $whereStr .=  ' ('.$key.' '.strtoupper($val[0]).' '.$this->parseValue($data[0]).' AND '.$this->parseValue($data[1]).' )';
                } elseif ($data[0]) {
                    $whereStr .= $key.' '.$this->comparison['gt'].' '.$this->parseValue($data[0]);
                } elseif ($data[1]) {
                    $whereStr .= $key.' '.$this->comparison['lt'].' '.$this->parseValue($data[1]);
                }
            }elseif(preg_match('/TIME/i',$val[0])){
                $data = is_string($val[1])? explode(',',$val[1]):$val[1];
                if($data[0] && $data[1]) {
                    $whereStr .=  ' ('.$key.' BETWEEN '.$this->parseValue($data[0]).' AND '.$this->parseValue($data[1] + 86400 -1).' )';
                } elseif ($data[0]) {
                    $whereStr .= $key.' '.$this->comparison['gt'].' '.$this->parseValue($data[0]);
                } elseif ($data[1]) {
                    $whereStr .= $key.' '.$this->comparison['lt'].' '.$this->parseValue($data[1] + 86400);
                }
            }else{
                $error = 'Model Error: args '.$val[0].' is error!';
                exit($error);
            }
        }else {
        	$whereStr .= $key.' = '.$this->parseValue($val);
        }
        return $whereStr;
    }

    protected function parseLimit($limit) {
        return !empty($limit)?   ' LIMIT '.$limit.' ':'';
    }

    protected function parseJoin($options = array()) {
        $joinStr = '';
        if (false === strpos($options['table'],',')) return null;
        $table = explode(',',$options['table']);
        $on = explode(',',$options['on']);
        $join = explode(',',$options['join']);
        $joinStr .= $table[0];
		for($i=0;$i<(count($table)-1);$i++){
        	$joinStr .= ' '.($join[$i]?$join[$i].' JOIN':'LEFT JOIN').' '.$table[$i+1].' ON '.($on[$i]?$on[$i]:'');
        }
        return $joinStr;
    }

    /**
     * sql关键字过滤
     * @param  array $options 拼接sql的参数数组
     * @return string
     */
	public function parseAttr($options){
		if (isset($options['attr'])){
			if (in_array(isset($options['attr']),array('LOW_PRIORITY','QUICK','IGNORE','HIGH_PRIORITY','SQL_CACHE','SQL_NO_CACHE'))){
				return $options['attr'].' ';
			}
		}else{
			return '';
		}
	}

	public function lockAttr($options){
		if (isset($options['attr'])){
			if (in_array($options['attr'],array('FOR UPDATE'))){
				return ' '.$options['attr'].' ';
			}
		}else{
			return '';
		}
	}

    /**
     * 清空表
     *
     * @param array $options
     * @return boolean
     */
	public function clear($options){
		$sql = 'TRUNCATE TABLE '.$this->parseTable($options);
		return Db::execute($sql,$this->db_index);
	}

    /**
     * 执行插入操作 insert
     * @param  array   $data     待插入数据
     * @param  array   $options  绑定的sql参数
     * @param  boolean $replace  是否使用REPLACE替换INSERT，避免主键冲突
     * @return boolean           插入成功or失败
     */
    public function insert($data,$options=array(),$replace=false) {
        $this->str_replace = true;//开启替换
        $values = $fields = array();
        foreach ($data as $key=>$val){
            $value  =  $this->parseValue($val);
            if(is_scalar($value)) {
                $values[] = $value;
                $fields[] = $key;
            }
        }
        $sql   =  ($replace?'REPLACE ':'INSERT ').$this->parseAttr($options).' INTO '.$this->parseTable($options).' ('.implode(',', $fields).') VALUES ('.implode(',', $values).')';
        return Db::execute($sql,$this->db_index);
    }

    /**
     * 执行修改操作 update
     * @param  array   $data     待修改数据
     * @param  array   $options  绑定的sql参数
     * @return boolean           修改成功or失败
     */
    public function update($data,$options) {
        $this->str_replace = true;//开启替换
        $sql   = 'UPDATE '
            .$this->parseAttr($options)
            .$this->parseTable($options)
            .$this->parseSet($data)
            .$this->parseWhere(isset($options['where'])?$options['where']:'')
            .$this->parseOrder(isset($options['order'])?$options['order']:'')
            .$this->parseLimit(isset($options['limit'])?$options['limit']:'');
            if (stripos($sql,'where') === false && $options['where'] !== true){
                //防止条件传错，更新所有记录
                return false;
            }
        return Db::execute($sql,$this->db_index);
    }

    /**
     * 执行删除操作 delete
     * @param  array   $options  绑定的sql参数
     * @return boolean           删除成功or失败
     */
    public function delete($options=array()) {
        $sql   = 'DELETE '.$this->parseAttr($options).' FROM '
            .$this->parseTable($options)
            .$this->parseWhere(isset($options['where'])?$options['where']:'')
            .$this->parseOrder(isset($options['order'])?$options['order']:'')
            .$this->parseLimit(isset($options['limit'])?$options['limit']:'');
            if (stripos($sql,'where') === false && $options['where'] !== true){
                //防止条件传错，删除所有记录
                return false;
            }
        return Db::execute($sql,$this->db_index);
    }

    /**
     * 直接执行sql语句
     * @param  string $sql sql语句
     * @return 执行结果
     */
    public function query($sql){
        return Db::query2data($sql,$this->db_index);
    }

    /**
     * 取得上一步插入产生的ID
     * @return int
     */
    public function getLastId() {
        return Db::getLastId($this->db_index);
    }

	/**
	 * 批量插入
	 */
    public function insertAll($datas,$options=array(),$replace=false) {
        $this->str_replace = true;//开启替换
        if(!is_array($datas[0])) return false;
        $fields = array_keys($datas[0]);
        $values = array();
        foreach ($datas as $data){
            $value = array();
            foreach ($data as $key=>$val){
                $val = $this->parseValue($val);
                if(is_scalar($val)) {
                    $value[] = $val;
                }
            }
            $values[] = '('.implode(',', $value).')';
        }
        $sql = ($replace?'REPLACE':'INSERT').' INTO '.$this->parseTable($options).' ('.implode(',', $fields).') VALUES '.implode(',',$values);
        return Db::execute($sql,$this->db_index);
    }

    /*
     * 插入重复时更新
     */
    public function insert_duplicate_updateAll($datas,$options=array(),$update_data=array()){
        $this->str_replace = true;//开启替换
        if(!is_array($datas[0])) return false;
        $fields = array_keys($datas[0]);
        $values = array();
        foreach ($datas as $data){
            $value = array();
            foreach ($data as $key=>$val){
                $val = $this->parseValue($val);
                if(is_scalar($val)) {
                    $value[] = $val;
                }
            }
            $values[] = '('.implode(',', $value).')';
        }
        $sql = 'INSERT INTO '.$this->parseTable($options).' ('.implode(',', $fields).') VALUES '.implode(',',$values).$this->parseDuplicate($update_data);
        return Db::execute($sql,$this->db_index);
    }

    protected function parseOrder($order) {
        if(is_array($order)) {
            $array   =  array();
            foreach ($order as $key=>$val){
                if(is_numeric($key)) {
                    $array[] =  $val;
                }else{
                    $array[] =  $key.' '.$val;
                }
            }
            $order   =  implode(',',$array);
        }
        return !empty($order)?  ' ORDER BY '.$order:'';
    }

    protected function parseGroup($group) {
        return !empty($group)? ' GROUP BY '.$group:'';
    }

    protected function parseHaving($having) {
        return  !empty($having)?   ' HAVING '.$having:'';
    }

    protected function parseDistinct($distinct) {
        return !empty($distinct)?   ' DISTINCT '.$distinct.',' :'';
    }

    /**
     * sql update语句set部分过滤组装
     * @param  array $data 要修改的键值对
     * @return string
     */
    protected function parseSet($data) {
        foreach ($data as $key=>$val){
            $value = $this->parseValue($val);
            if(is_scalar($value))
                $set[] = $key.'='.$value;
        }
        return ' SET '.implode(',',$set);
    }

    protected function parseDuplicate($data) {
        foreach ($data as $key=>$val){
            $value   =  $this->parseValue($val);
            if(is_scalar($value))
                $set[]    = $key.'='.$value;
        }
        return ' ON DUPLICATE KEY UPDATE '.implode(',',$set);
    }

    public function escapeString($str) {
        if ($this->str_replace) {
            $danger_word_arr = ['/insert/i','/update/i','/drop/i','/delete/i','/create/i','/alter/i','/sleep/i','/benchmark/i','/load_file/i','/outfile/i'];
            $danger_word_replace_arr = ['i-nser-t','u-pdat-e','d-ro-p','d-elet-e','c-reat-e','a-lte-r','s-lee-p','b-enchmar-k','l-oad_fil-e','o-utfil-e'];
            $replace_times = 0;
            $str = preg_replace($danger_word_arr, $danger_word_replace_arr, $str,'-1',$replace_times);
            if ($replace_times > 0) {
                $error = "黑客尝试,来源ip:".getIp();
                Log::record($error."\r\n".$str,'DANGER');
            }
        }
    	return addslashes($str);//防注入 加反斜杠
    }

    public function checkActive($host) {
    	Db::ping($host);
    }
}