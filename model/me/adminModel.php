<?php
namespace model\me;

use framework\lib\Model;

class adminModel extends Model
{
    /*
     * 添加用户
     */
    public function addAdmin($data,$get_id=false)
    {
        return $this->table('admin')->insert($data,$get_id);
    }

    /*
     * 获取用户
     */
    public function getAdmin($condition,$field="*")
    {
        return $this->table('admin')->where($condition)->field($field)->get();
    }

    /*
     * 修改用户
     */
    public function editAdmin($condition,$data){
        return $this->table("admin")->where($condition)->update($data);
    }

    /*
     * 获取用户列表
     */
	public function getAdminList($condition,$field="*",$page=null)
	{
		return $this->table('admin')->where($condition)->field($field)->page($page)->select();
	}
}