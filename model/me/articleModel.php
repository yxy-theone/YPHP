<?php
namespace model\me;

use framework\lib\Model;

class articleModel extends Model
{
    /*
     * 添加文章
     */
    public function addArticle($data,$get_id=false)
    {
        return $this->table('article')->insert($data,$get_id);
    }

    /*
     * 获取文章
     */
    public function getArticle($condition,$field="*")
    {
        return $this->table('article')->where($condition)->field($field)->get();
    }

    /*
     * 修改文章
     */
    public function editArticle($condition,$data){
        return $this->table('article')->where($condition)->update($data);
    }

    /*
     * 删除文章
     */
    public function delArticle($condition){
        return $this->table('article')->where($condition)->delete();
    }

    /*
     * 获取文章列表
     */
	public function getArticleList($condition,$field="*",$page=null,$order="id desc")
	{
		return $this->table('article')->where($condition)->field($field)->page($page)->order($order)->select();
	}
}