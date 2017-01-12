<?php
namespace model\me;

use framework\lib\Model;
use framework\lib\Cache;

/**
 * 博客模型
 */
class article_categoryModel extends Model
{
    /*
     * 获取文章类型列表
     */
    public function getCategoryList()
    {
        return $this->table('article_category')->field('*')->select();
    }

    /**
     * 返回id=>name的文章类别一维数组
     * 默认 参数$id 为空则返回全部
     */
    public function getCategory($id = false){
        $category_arr = Cache::get("category_arr");
        if (empty($category_arr)) {
            $category = $this->getCategoryList();
            $category_arr = [];
            foreach ($category as $v) {
                $category_arr[$v['id']] = $v['name'];
            }
            Cache::set("category_arr", $category_arr);
        }
        if ($id) {
            return $category_arr[$id];
        }
        return $category_arr;
    }

    /*
     * 添加文章类型
     */
    public function addCategory($data)
    {
        return $this->table('article_category')->insert($data);
    }

    /*
     * 修改文章类型
     */
    public function editCategory($condition,$data)
    {
        return $this->table('article_category')->where($condition)->update($data);
    }

    /*
     * 删除文章类型
     */
    public function delCategory($condition)
    {
        return $this->table('article_category')->where($condition)->delete();
    }
}