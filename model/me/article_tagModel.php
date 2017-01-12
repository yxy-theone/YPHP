<?php
namespace model\me;

use framework\lib\Model;
use framework\lib\Cache;

/**
 * 博客模型
 */
class article_tagModel extends Model
{
    /*
     * 获取文章标签列表
     */
    public function getTagList()
    {
        return $this->table('article_tag')->field('*')->select();
    }

    /**
     * 返回id=>[name,color]的文章标签二维数组
     * 默认 参数$id 为空则返回全部
     */
    public function getTag($id = false){
        $tag_arr = Cache::get("tag_arr");
        if (empty($tag_arr)) {
            $tag = $this->getTagList();
            $tag_arr = [];
            foreach ($tag as $v) {
                $tag_arr[$v['id']] = ['name'=>$v['name'],'color'=>$v['color']];
            }
            Cache::set("tag_arr", $tag_arr);
        }
        if ($id) {
            return $tag_arr[$id];
        }
        return $tag_arr;
    }

    /*
     * 添加文章标签
     */
    public function addTag($data)
    {
        return $this->table('article_tag')->insert($data);
    }

    /*
     * 修改文章标签
     */
    public function editTag($condition,$data)
    {
        return $this->table('article_tag')->where($condition)->update($data);
    }

    /*
     * 删除文章标签
     */
    public function delTag($condition)
    {
        return $this->table('article_tag')->where($condition)->delete();
    }
}