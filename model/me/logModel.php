<?php
namespace model\me;

use framework\lib\Model;

class logModel extends Model
{
    /*
     * 写日志
     */
    public function addLog($data)
    {
        return $this->table('log')->insert($data);
    }
}